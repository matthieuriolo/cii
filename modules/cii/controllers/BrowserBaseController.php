<?php

namespace app\modules\cii\controllers;

use Yii;

use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

use app\modules\cii\Permission;
use app\modules\cii\models\browser\UploadFileForm;
use app\modules\cii\models\browser\RenameFileForm;

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

        return $this->goBackToReferrer();
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

        return $this->goBackToReferrer();
    }


    public function actionUpload($path = null) {
        $basePath = Yii::$app->basePath;
        $dstPath = realpath($basePath . '/' . $path);
        if(strpos($dstPath, $basePath) !== 0) {
            throw new SecurityException();
        }


        $uploadModel = new UploadFileForm();
        $uploadModel->files = UploadedFile::getInstances($uploadModel, 'files');
        foreach($uploadModel->files as $file) {
            $filePath = $dstPath . '/' . $file->name;
            $size = (int)Yii::$app->cii->package->setting('cii', 'size_uploaded_image');

            if(!
                (Yii::$app->cii->package->setting('cii', 'resize_uploaded_image')
                && $size > 0
                && ($img = Yii::$app->cii->image->load($file->tempName))
                && (($img->height > $size) || ($img->width > $size))
                && $img->resize($size, null, \cii\components\drivers\Kohana\Image::ADAPT)
                && $img->save($filePath)
                )
            ) {
                $file->saveAs($filePath);
            }
        }
        
        return $this->goBackToReferrer();
    }
}
