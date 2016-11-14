<?php

namespace app\modules\cii\controllers;

use Yii;
use cii\backend\BackendController as Controller;
use cii\web\SecurityException;
use cii\base\SearchModel;

use app\modules\cii\models\common\Route;
use app\modules\cii\models\common\Classname;
use app\modules\cii\models\route\CaptchaRoute;
use app\modules\cii\Permission;

use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;

use cii\helpers\SPL;

class RouteController extends Controller {
    public function getAccessRoles() {
        return [Permission::MANAGE_ROUTE, Permission::MANAGE_ADMIN];
    }

	public function behaviors() {
        return parent::behaviors() + [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'lazy' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($parent = null) {
        $query = Route::find()
            ->joinWith([
                'language as language',
                'classname as classname',
                'classname.package.extension as ext'
            ])
            ->where([
                'parent_id' => $parent,
                'ext.enabled' => true
            ])
        ;


        $model = new SearchModel(Route::className());
        $model->stringFilter('slug');
        $model->booleanFilter('enabled');
        
        if(Yii::$app->cii->package->setting('cii', 'multilanguage')) {
            $model->languageFilter('language_id');
        }

        if($model->load(Yii::$app->request->get()) && $model->validate()) {
            $query = $model->applyFilter($query);
    	}


        $data = new ActiveDataProvider([
    		'query' => $query,
			'sort' => [

                'attributes' => [
                    'slug',
                    'hits',
                    'created',
                    'language' => [
                        'asc' => ['language.name' => SORT_ASC],
                        'desc' => ['language.name' => SORT_DESC],
                    ],

                    'classname' => [
                        'asc' => ['classname.path' => SORT_ASC],
                        'desc' => ['classname.path' => SORT_DESC],
                    ],

                    'enabled'
                ],
        	],
		]);

        return $this->render('index', [
            'model' => $model,
        	'data' => $data,
        	'parent' => Route::find()->where(['id' => $parent])->one()
        ]);
    }

    public function actionView($id) {
    	return $this->render('view', [
        	'model' => Route::find()->where(['id' => $id])->one()
        ]);
    }

    public function actionDelete($id) {
    	/*
		we need to make a special check here
			- make sure admin is still available
    	*/
    	$model = Route::findOne(['id' => $id]);
    	$parent = $model->parent_id;
        /*if($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }*/

        if($model->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->cii->route->clearCache();
        $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/route/index'), ['parent' => $parent]]);
    }

    public function actionCreate($parent = null) {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        
        $topmodel = null;
        $model = new Route();


        $types = Yii::$app->cii->route->getTypes();
        $data = Yii::$app->request->post();

        if(!isset($data[$model->formName()], $data[$model->formName()]['parent_id'])) {
            $model->parent_id = $parent;
        }
        
        try {
            if($data) {
            	if(isset($data[$model->formName()], $data[$model->formName()]['type']) && !empty($data[$model->formName()]['type'])) {
                    if(!isset($types[$data[$model->formName()]['type']])) {
                        throw new SecurityException();
                    }

                    $model->classname_id = Classname::registerModel($data[$model->formName()]['type']);
                }

                $type = $data[$model->formName()]['type'];
                $topmodel = new $type();
                
                $modelValid = $model->load($data);
                $topmodelValid = $topmodel->load($data);
                

            	if($modelValid && $topmodelValid && $model->save()) {
                    $attr = $type::getOutboxAttribute($model->className());
                    $topmodel->$attr = $model->id;
                    
                    if($topmodel->save()) {
        				Yii::$app->cii->route->clearCache();
            			$this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/route/view'), ['id' => $model->id]]);
                	    $transaction->commit();
                        return;
                    }
                }
            }
        }catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $transaction->rollBack();
        return $this->render('create', [
        	'parentId' => $parent,
            'model' => $model,
            'topmodel' => $topmodel,
        ]);
    }

    public function actionUpdate($id) {
        if(($model = Route::findOne($id)) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();

        $topmodel = $model->outbox();
        $old_type = $model->classname->path;
        $types = Yii::$app->cii->route->getTypes();
        $data = Yii::$app->request->post();

        try {
            if($data) {
                if(isset($data[$model->formName()], $data[$model->formName()]['type']) && !empty($data[$model->formName()]['type'])) {
                    if(!isset($types[$data[$model->formName()]['type']])) {
                        throw new SecurityException();
                    }

                    $model->classname_id = Classname::registerModel($data[$model->formName()]['type']);
                }

                $type = $data[$model->formName()]['type'];
                    
                if($old_type != $type && $old_type) {
                    $topmodel = new $type();
                    $old_topmodel = $old_type::findOne([$old_type::getOutboxAttribute($model->className()) => $model->id]);
                    if(!$old_topmodel || !$old_topmodel->delete()) {
                        throw new \Exception("Failure deleting old model");
                    }
                }else {
                    $topmodel = $type::findOne([$type::getOutboxAttribute($model->className()) => $model->id]);
                }

                $data[$topmodel->formName()][$type::getOutboxAttribute($model->className())] = $model->id;

                $modelValid = $model->load($data);
                $topmodelValid = $topmodel->load($data);

                if($modelValid && $topmodelValid && $model->save() && $topmodel->save()) {
                    Yii::$app->cii->route->clearCache();
                    $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/route/view'), 'id' => $model->id]);
                    $transaction->commit();
                    return;
                }
            }
        }catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $transaction->rollBack();
        return $this->render('update', [
            'model' => $model,
            'topmodel' => $topmodel,
        ]);
    }

    public function actionLazy() {
    	$data = Yii::$app->request->post();
    	$types = Yii::$app->cii->route->getTypes();

    	if(!isset($types[$data['class']])) {
			throw new SecurityException();
		}

		if(!SPL::hasInterface($data['class'], 'app\modules\cii\base\LazyModelInterface')) {
			throw new ServerErrorHttpException('Invalid route class ' . $data['class']);
		}

		$info = $data['class']::getLazyCRUD();
		return Json::encode([
			'label' => $info['controller']->$info['label'](),
			'content' => $info['controller']->$info['create']()
		]);
    }





    public function getLazyLabel() {
        return '<i class="glyphicon glyphicon-qrcode"></i> Captcha';
    }

    public function getLazyCreate($model = null, $form = null) {
        if(!$model) {
            $model = new CaptchaRoute();
        }

        return $this->renderAjax('_form_captcharoute', [
            'model' => $model,
            'form' => $form ?: ActiveForm::begin()
        ]);
    }

    public function getLazyUpdate($model, $form) {
        return $this->renderAjax('_form_captcharoute', [
            'model' => $model,
            'form' => $form
        ]);
    }

    public function getLazyView($model) {
        return $this->renderAjax('_view_captcharoute', [
            'model' => $model
        ]);
    }
}
