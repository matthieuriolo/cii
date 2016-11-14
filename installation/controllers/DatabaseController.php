<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\web\Controller;
use app\helpers\CiiPathHelper;
use app\models\DatabaseForm;

use app\base\PackageReflection;




require_once(__DIR__ . '/../../modules/cii/models/common/classname.php');
require_once(__DIR__ . '/../../modules/cii/models/extension/extension.php');
require_once(__DIR__ . '/../../modules/cii/models/extension/package.php');
use app\modules\cii\models\Extension as Ext;

require_once(__DIR__ . '/../../modules/cii/models/auth/Language.php');
require_once(__DIR__ . '/../../modules/cii/models/auth/Layout.php');
require_once(__DIR__ . '/../../modules/cii/models/auth/User.php');
require_once(__DIR__ . '/../../modules/cii/models/auth/UserCreateForm.php');
use app\modules\cii\models\UserCreateForm as User;

class DatabaseController extends Controller {
    public function actionIndex() {
        $model = new DatabaseForm();
        $data = Yii::$app->request->post();
        if($model->load($data) && $model->validate()) {
            if(isset($data['database-button'])) {
                $p = __DIR__ . '/../../config/db.php';
                $helper = new CiiPathHelper();
                $helper->replaceParameterInFile(
                    $p,
                    'username',
                    $model->username
                );

                $helper->replaceParameterInFile(
                   $p,
                    'password',
                    $model->password
                );

                $helper->replaceParameterInFile(
                    $p,
                    'dsn',
                    $model->mode . ':host=' . $model->host . ';dbname=' . $model->dbname
                );

                $this->redirect(['database/init']);
                return;
            }
        }
		
        return $this->render('index', [
            'model' => $model
        ]);
    }
    
    public function actionInstall() {
        $path = __DIR__ . '/../../modules';
        
        foreach($this->defaultPackages() as $pkgName) {
            $ref = new PackageReflection();
            if($ref->load($path . '/' . $pkgName)) {
                $ref->install(true);
            }
        }

        $this->redirect(['database/init']);
        return;
    }


    protected function defaultPackages() {
        return [
            'cii'
        ];
    }

    public function actionInit() {
        $installed = false;

        try {
            $rows = Ext::find()->joinWith('classname as classname')->where([
                'name' => 'cii',
                'classname.path' => 'app\modules\cii\models\Package'
            ])->one();
            if(!empty($rows)) {
                $installed = true;
            }
        }catch(\Exception $e) {
            throw $e;
        }

        return $this->render('init', [
            'installed' => $installed
        ]);
    }


    public function actionUser() {
        $model = new User();
        $model->superadmin = true;
        $model->enabled = true;
        
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->redirect(['paths/after']);
            return;
        }

        return $this->render('user', [
            'model' => $model
        ]);
    }
}
