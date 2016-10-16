<?php

namespace app\modules\cii\controllers;

use Yii;

use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;


use app\modules\cii\models\Route;
use app\modules\cii\models\ContentRoute;
use app\modules\cii\Permission;
use app\modules\cii\models\UploadFileForm;
use app\modules\cii\models\RenameFileForm;

use cii\backend\BackendController;
use cii\web\SecurityException;
use cii\base\SearchModel;

class BrowserBaseController extends BackendController {
    public function behaviors() {
        return parent::behaviors() + [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'upload' => ['POST'],
                ],
            ],
        ];
    }

    public function actionDownload($path = null) {
        $basePath = Yii::$app->basePath . '/web';
        $dstPath = realpath($basePath . '/' . $path);
        if(strpos($dstPath, $basePath) !== 0) {
            throw new SecurityException();
        }
        
        if(is_dir($dstPath)) {
            throw new SecurityException();
        }

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-Disposition: attachment; filename=\"" . basename($path) . "\""); 
        readfile($dstPath);
        die();
    }

    public function actionRemove($path) {
        $basePath = Yii::$app->basePath . '/web';
        $dstPath = realpath($basePath . '/' . $path);
        if(strpos($dstPath, $basePath) !== 0) {
            throw new SecurityException();
        }

        if(is_dir($dstPath)) {
            FileHelper::removeDirectory($dstPath);
        }else {
            @unlink($dstPath);
        }

        return $this->goBackToReferrer();
    }

    public function actionRename($path = null) {
        $basePath = Yii::$app->basePath . '/web';
        $dstPath = realpath($basePath . '/' . $path);
        if(strpos($dstPath, $basePath) !== 0) {
            throw new SecurityException();
        }

        $renameModel = new RenameFileForm();
        if($renameModel->load(Yii::$app->request->post()) && $renameModel->validate()) {
            rename($dstPath . '/' . $renameModel->original, $dstPath . '/' . $renameModel->name);
        }

        return $this->goBackToReferrer();
    }


    public function actionUpload($path = null) {
        $basePath = Yii::$app->basePath . '/web';
        $dstPath = realpath($basePath . '/' . $path);
        if(strpos($dstPath, $basePath) !== 0) {
            throw new SecurityException();
        }


        $uploadModel = new UploadFileForm();
        $uploadModel->files = UploadedFile::getInstances($uploadModel, 'files');
        foreach($uploadModel->files as $file) {
            $filePath = $dstPath . '/' . $file->baseName . '.' . $file->extension;

            if(!is_file($filePath)) {
                $file->saveAs($filePath);
            }
        }
        
        return $this->goBackToReferrer();
    }
}
