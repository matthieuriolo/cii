<?php

namespace app\modules\cii\controllers;

use Yii;
use app\modules\cii\models\ContentRoute;
use app\modules\cii\models\Content;
use app\modules\cii\models\ContentSearch;
use app\modules\cii\models\Classname;

use cii\web\SecurityException;
use cii\backend\BackendController;

use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

use yii\helpers\Json;

use cii\helpers\SPL;


/**
 * ContentController implements the CRUD actions for Content model.
 */
class ContentController extends BackendController {
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
    public function actionIndex()
    {
        $searchModel = new ContentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Content model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Content model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($parent = null) {
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
                        $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/content/view'), ['id' => $model->id]]);
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

            'types' => Yii::$app->cii->layout->getContentTypesForDropdown(),
        ]);
    }

    /**
     * Updates an existing Content model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    public function actionUpdate($id) {
        if(($model = Content::findOne($id)) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();

        $topmodel = $model->outbox();
        $old_type = $model->type = $model->classname->path;
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
                    $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/content/view'), 'id' => $model->id]);
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
            'types' => Yii::$app->cii->layout->getContentTypesForDropdown(),
        ]);
    }

    /**
     * Deletes an existing Content model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
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
        return $this->renderAjax('_update_contentroute', [
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
