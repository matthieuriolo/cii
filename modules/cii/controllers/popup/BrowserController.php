<?php

namespace app\modules\cii\controllers\popup;

use Yii;
use yii\data\ActiveDataProvider;

use cii\base\SearchModel;
use cii\backend\BackendController;

use app\modules\cii\Permission;
use app\modules\cii\models\browser\UploadFileForm;
use app\modules\cii\models\browser\RenameFileForm;
use app\modules\cii\controllers\BrowserBaseController;

class BrowserController extends BrowserBaseController {
    public $baseModule = 'modules/cii/popup/browser/';

    public function getAccessRoles() {
        return [Permission::MANAGE_GROUP, Permission::MANAGE_ADMIN];
    }

    public function actionIndex($path = null) {
        $basePath = Yii::$app->basePath . '/web';

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
                'class' => 'app\modules\cii\models\browsercell',
                'baseUrl' => Yii::$app->seo->relativeAdminRoute('modules/cii/popup/browser'),
                'basePath' => $basePath,
                'file' => $basePath . '/' . ($path ? $path . '/' : '') . $file
            ]);
        }, $files);


        $uploadModel = new UploadFileForm();
        $renameModel = new RenameFileForm();


        return $this->renderRaw('index', [
            'path' => $path,
            'files' => $files,
            'uploadModel' => $uploadModel,
            'renameModel' => $renameModel
        ]);
    }
}
