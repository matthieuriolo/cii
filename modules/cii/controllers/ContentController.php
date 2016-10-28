<?php

namespace app\modules\cii\controllers;

use Yii;
use app\modules\cii\models\RedirectRoute;
use app\modules\cii\models\ContentRoute;
use app\modules\cii\models\Content;
use app\modules\cii\models\ContentSearch;
use app\modules\cii\models\Classname;
use app\modules\cii\models\ContentVisibilities;

use cii\web\SecurityException;
use cii\backend\BackendController;
use cii\helpers\SPL;
use cii\base\SearchModel;


use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;


/**
 * ContentController implements the CRUD actions for Content model.
 */
class ContentController extends BackendController {
    public function getAccessRoles() {
        return [Permission::MANAGE_CONTENT, Permission::MANAGE_ADMIN];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'lazy' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Content models.
     * @return mixed
     */
    public function actionIndex($pjaxid = null) {
        $query = Content::find();


        $query->joinWith([
            'classname as classname',
            'classname.package.extension as package'
        ]);
        $query->andFilterWhere([
            'package.enabled' => true,
        ]);

        $model = new SearchModel(Content::className());
        $model->stringFilter('name');
        $model->booleanFilter('enabled');
        $model->pjaxid = $pjaxid;

        if($model->load(Yii::$app->request->get()) && $model->validate()) {
            $query = $model->applyFilter($query);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'name',
                    
                    'classname' => [
                        'asc' => ['classname.path' => SORT_ASC],
                        'desc' => ['classname.path' => SORT_DESC],
                    ],
                    'created',
                    'enabled'
                ],
            ],
        ]);

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'pjaxid' => $pjaxid
        ]);
    }

    /**
     * Displays a single Content model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $pjaxid = null) {
        $visibleModel = new ContentVisibilities();
        $visibleModel->content_id = $id;

        if($visibleModel->load(Yii::$app->request->post()) && $visibleModel->save()) {
            $visibleModel = new ContentVisibilities();
        }

        $visibilities = new ActiveDataProvider([
            'query' => ContentVisibilities::find()->where([
                'content_id' => $id
            ])->joinWith(['route as route']),
            'sort' => [
                'attributes' => [
                    'position',
                    'ordering',
                    'route' => [
                        'asc' => ['route.slug' => SORT_ASC],
                        'desc' => ['route.slug' => SORT_DESC],
                    ]
                ],
            ],
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'visibilities' => $visibilities,

            'visibleModel' => $visibleModel,
            'routes' => Yii::$app->cii->route->getRoutesForDropdown(),
            'positions' => Yii::$app->cii->layout->getPositionsForDropdown(),
            'languages' => Yii::$app->cii->language->getLanguagesForDropdown(),

            'pjaxid' => $pjaxid,
        ]);
    }

    /**
     * Creates a new Content model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($parent = null, $pjaxid = null) {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        
        $topmodel = null;
        $model = new Content();


        $types = Yii::$app->cii->layout->getContentTypes();
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
                $topmodel = new $type();
                
                $modelValid = $model->load($data);
                $topmodelValid = $topmodel->load($data);
                
                if($modelValid && $topmodelValid && $model->save()) {
                    $attr = $type::getOutboxAttribute($model->className());
                    $topmodel->$attr = $model->id;
                    
                    if($topmodel->save()) {
                        Yii::$app->cii->layout->clearCache();
                        $transaction->commit();
                        if($pjaxid) {
                            return $this->run("content/view", [
                                'id' => $model->id,
                                'pjaxid' => $pjaxid
                            ]);
                        }else {
                            return $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/content/view'), ['id' => $model->id]]);
                        }
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

            'types' => Yii::$app->cii->layout->getContentTypesForDropdown(),
            'pjaxid' => $pjaxid,
        ]);
    }

    /**
     * Updates an existing Content model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    public function actionUpdate($id, $pjaxid = null) {
        if(($model = Content::findOne($id)) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();

        $topmodel = $model->outbox();
        $old_type = $model->classname->path;
        $types = Yii::$app->cii->layout->getContentTypes();
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
                    Yii::$app->cii->layout->clearCache();
                    $transaction->commit();
                    if($pjaxid) {
                        return $this->run("content/view", [
                            'id' => $model->id,
                            'pjaxid' => $pjaxid
                        ]);
                    }else {
                        return $this->redirect([
                            Yii::$app->seo->relativeAdminRoute('modules/cii/content/view'),
                            'id' => $model->id
                        ]);
                    }
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
            'types' => Yii::$app->cii->layout->getContentTypesForDropdown(),
            'pjaxid' => $pjaxid
        ]);
    }

    /**
     * Deletes an existing Content model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $pjaxid = null) {
        $this->findModel($id)->delete();
        Yii::$app->cii->layout->clearCache();
        $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/content/index')]);
        return;
    }

    /**
     * Finds the Content model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Content the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Content::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function getLazyRedirectLabel() {
        return '<i class="glyphicon glyphicon-arrow-right"></i> Redirect';
    }

    public function getLazyRedirectCreate($model = null, $form = null) {
        if(!$model) {
            $model = new RedirectRoute();
        }

        return $this->renderAjax('_form_redirectroute', [
            'model' => $model,
            'routes' => Yii::$app->cii->route->getRoutesForDropdown(),
            'form' => $form ?: ActiveForm::begin()
        ]);
    }

    public function getLazyRedirectUpdate($model, $form) {
        return $this->renderAjax('_form_redirectroute', [
            'model' => $model,
            'routes' => Yii::$app->cii->route->getRoutesForDropdown(),
            'form' => $form
        ]);
    }

    public function getLazyRedirectView($model) {
        return $this->renderAjax('_view_redirectroute', [
            'model' => $model
        ]);
    }








    public function getLazyLabel() {
        return '<i class="glyphicon glyphicon-file"></i> Content';
    }

    public function getLazyCreate($model = null, $form = null) {
        if(!$model) {
            $model = new ContentRoute();
        }

        return $this->renderAjax('_form_contentroute', [
            'model' => $model,
            'contents' => Yii::$app->cii->layout->getContentsForDropdown(),
            'form' => $form ?: ActiveForm::begin()
        ]);
    }

    public function getLazyUpdate($model, $form) {
        return $this->renderAjax('_form_contentroute', [
            'model' => $model,
            'contents' => Yii::$app->cii->layout->getContentsForDropdown(),
            'form' => $form
        ]);
    }

    public function getLazyView($model) {
        return $this->renderAjax('_view_contentroute', [
            'model' => $model
        ]);
    }



    public function actionLazy() {
        $data = Yii::$app->request->post();
        $types = Yii::$app->cii->layout->getContentTypes();

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
}
