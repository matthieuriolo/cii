<?php

namespace app\modules\cii\controllers;

use Yii;

use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;



use app\modules\cii\Permission;
use app\modules\cii\models\common\Route;
use app\modules\cii\models\route\ContentRoute;
use app\modules\cii\models\browser\UploadFileForm;
use app\modules\cii\models\browser\RenameFileForm;

use cii\web\SecurityException;
use cii\base\SearchModel;

class BrowserController extends BrowserBaseController {
    public function getAccessRoles() {
        return [Permission::MANAGE_BROWSER, Permission::MANAGE_ADMIN];
    }
    
    public function actionIndex($path = null) {
        $basePath = Yii::$app->basePath;

        if($path) {
            $apath = realpath($basePath . '/' . $path);
            if(strpos($apath, $basePath) !== 0 || !is_dir($apath)) {
                throw new SecurityException();
            }
        }


        $files = scandir($basePath . '/' . $path);
        $files = array_filter($files, function($file) {
            if(substr($file, 0, 1) === '.') {
                return false;
            }

            return true;
        });

        $files = array_map(function($file) use($path, $basePath) {
            return Yii::createObject([
                'class' => 'app\modules\cii\models\browser\browsercell',
                'basePath' => $basePath,
                'file' => $basePath . '/' . ($path ? $path . '/' : '') . $file
            ]);
        }, $files);


        $uploadModel = new UploadFileForm();
        $renameModel = new RenameFileForm();


        return $this->render('index', [
            'path' => $path,
            'files' => $files,
            'uploadModel' => $uploadModel,
            'renameModel' => $renameModel
        ]);
    }
/*
    
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
    
    public function actionDownload($path = null) {
        $basePath = Yii::$app->basePath;
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
        $basePath = Yii::$app->basePath;
        $dstPath = realpath($basePath . '/' . $path);
        if(strpos($dstPath, $basePath) !== 0) {
            throw new SecurityException();
        }

        if(is_dir($dstPath)) {
            FileHelper::removeDirectory($dstPath);
        }else {
            @unlink($dstPath);
        }

        $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/browser/index'), 'path' => dirname($path)]);
    }

    public function actionRename($path = null) {
        $basePath = Yii::$app->basePath;
        $dstPath = realpath($basePath . '/' . $path);
        if(strpos($dstPath, $basePath) !== 0) {
            throw new SecurityException();
        }

        $renameModel = new RenameFileForm();
        if($renameModel->load(Yii::$app->request->post()) && $renameModel->validate()) {
            rename($dstPath . '/' . $renameModel->original, $dstPath . '/' . $renameModel->name);
        }

        $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/browser/index'), 'path' => $path]);
    }


    public function actionUpload($path = null) {
        $basePath = Yii::$app->basePath;
        $dstPath = realpath($basePath . '/' . $path);
        if(strpos($dstPath, $basePath) !== 0) {
            throw new SecurityException();
        }


        $uploadModel = new UploadFileForm();
        if (Yii::$app->request->isPost) {
            $uploadModel->files = UploadedFile::getInstances($uploadModel, 'files');
        
            foreach($uploadModel->files as $file) {
                $filePath = $dstPath . '/' . $file->baseName . '.' . $file->extension;

                if(!is_file($filePath)) {
                    $file->saveAs($filePath);
                }
            }
        }
        $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/browser/index'), 'path' => $path]);
    }*/
}
