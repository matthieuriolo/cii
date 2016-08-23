<?php

namespace app\modules\cii\controllers;

use Yii;

use cii\backend\BackendController as Controller;
use cii\base\PackageReflection;
use cii\web\SecurityException;

use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\data\ActiveDataProvider;

use app\modules\cii\models\Configuration as Core_Settings;
use app\modules\cii\models\UploadExtensionForm;
use app\modules\cii\models\Extension;



abstract class ExtensionBaseController extends Controller {
    const FILENAME = 'extension.zip';
    const DIRNAME = 'extension.dir';

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    abstract protected function getModel($id);
    abstract protected function getDataProvider();
    abstract protected function getModelType();
    abstract protected function getModelUrl();

    public function actionIndex() {
        return $this->render('index', [
        	'data' => $this->getDataProvider()
        ]);
    }

    public function actionView($id) {
        $model = $this->getModel($id);
        return $this->render('/extension/view', [
            'modelType' => $this->getModelType(),
            'modelUrl' => $this->getModelUrl(),
            'model' => $model,
            'settings' => $this->getExtensionSettings($id)
        ]);
    }

    protected function getExtensionSettings($id) {
        return new ActiveDataProvider([
            'query' => Core_Settings::find()->where(['extension_id' => $id]), 
            'sort' => [
                'attributes' => ['name', 'value'],
            ],
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


    public function actionEnable($id, $back) {
        $module = Extension::findOne($id);
        $module->enabled = true;
        $module->save();

        Yii::$app->cii->package->clearCache();
        $this->redirect($back);
    }

    public function actionDisable($id, $back) {
        $module = Extension::findOne($id);
        if($name == 'cii' && ($module->layout || $module->package)) {
            throw new SecurityException();
        }

        $module->enabled = false;
        $module->save();

        Yii::$app->cii->package->clearCache();
        $this->redirect($back);
    }
}
