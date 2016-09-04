<?php

namespace app\modules\cii\controllers;

use Yii;

use cii\backend\BackendController as Controller;
use cii\base\PackageReflection;
use cii\web\SecurityException;


use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\helpers\FileHelper;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

use app\modules\cii\models\Configuration as Core_Settings;
use app\modules\cii\models\UploadExtensionForm;
use app\modules\cii\models\Extension;
use app\modules\cii\models\ExtensionSearchModel as SearchModel;
use app\modules\cii\models\SettingSearchModel;


abstract class ExtensionBaseController extends Controller {
    const FILENAME = 'extension.zip';
    const DIRNAME = 'extension.dir';

    public function behaviors() {
        return parent::behaviors() + [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    abstract protected function getModel($id);
    abstract protected function getDataProvider($searchModel = null);
    abstract protected function getModelType();
    abstract protected function getModelUrl();

    protected function getModelClass() {
        return Extension::className();
    }

    public function actionIndex() {
        $model = new SearchModel($this->getModelClass());
        $model->stringFilter('name');
        $model->booleanFilter('enabled');
        

        return $this->render('index', [
        	'data' => $this->getDataProvider($model),
            'model' => $model,
        ]);
    }

    public function actionView($id) {
        $model = $this->getModel($id);

        $models = $model->settings;

        $searchModel = new SettingSearchModel();
        $searchModel->stringFilter('name', ['name', 'value']);
        $searchModel->typeFilter('type');
        
        if($searchModel->load(Yii::$app->request->get()) && $searchModel->validate()) {
            $models = $searchModel->filterArray($models);
        }


        return $this->render('/extension/view', [
            'modelType' => $this->getModelType(),
            'modelUrl' => $this->getModelUrl(),
            'model' => $model,
            'searchModel' => $searchModel,
            'settings' => new ArrayDataProvider([
                'allModels' => $models,
                'sort' => [
                    'attributes' => [
                        'label',
                        'id',
                        'type',
                        'default',
                        'value'
                    ]
                ]
            ])
        ]);
    }

    public function actionInstall() {
        $model = new UploadExtensionForm();

        if($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file');
            $filePath = Yii::$app->params['uploadDirectory'] . '/' . self::FILENAME;
            
            if(($file instanceof UploadedFile) && $file->saveAs($filePath)) {
                $zipDir = Yii::$app->params['uploadDirectory'] . '/' . self::DIRNAME;
                if(is_dir($zipDir)) {
                    FileHelper::removeDirectory($zipDir);
                }

                FileHelper::createDirectory($zipDir);

                $zip = new \ZipArchive();
                if($zip->open($filePath) === true) {
                    $zip->extractTo($zipDir);
                    $zip->close();

                    unlink($filePath);


                    $files = FileHelper::findFiles($zipDir, [
                        'only' => ['index.php']
                    ]);

                    if(count($files)) {
                        $pkg = new PackageReflection();
                        $pkg->load(dirname($files[0]));

                        if(($ret = $pkg->install()) !== true) {
                            if(is_array($ret)) {
                                foreach($ret as $error) {
                                    Yii::$app->session->setFlash('danger', $error);
                                }
                            }
                        }else {
                            $this->redirect($this->getModelUrl());
                            return;
                        }
                    }
                }
            }
        }

        return $this->render('/extension/install', [
            'model' => $model,
            'modelType' => $this->getModelType(),
            'modelUrl' => $this->getModelUrl(),
        ]);
    }


    public function actionEnable($id) {
        $module = $this->findExtension($id);
        $module->enabled = true;
        $module->save();
        
        $this->redirect($this->getModelUrl());
    }

    public function actionDisable($id) {
        $module = $this->findExtension($id);
        if($module->name == 'cii' && ($module->layout || $module->package)) {
            throw new SecurityException();
        }

        $module->enabled = false;
        $module->save();

        $this->redirect($this->getModelUrl());
    }

    protected function findExtension($id) {
        if(($model = Extension::findOne($id)) !== null) {
            return $model;
        }else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
