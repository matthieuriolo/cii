<?php

namespace app\modules\cii\controllers;

use Yii;
use cii\backend\BackendController as Controller;
use app\modules\cii\models\extension\Package as Core_Module;
use app\modules\cii\models\extension\Configuration as Core_Settings;
use app\modules\cii\models\auth\LoginForm;
use app\modules\cii\Permission;

use cii\db\DbDumper;


use yii\log\FileTarget;
use cii\helpers\FileHelper;
use yii\data\ArrayDataProvider;

class BackendController extends Controller {
    public function getAccessRoles() {
        return [
            Permission::MANAGE_LOG,
            Permission::MANAGE_ADMIN,
            [
                'actions' => ['login', 'captcha'],
                'allow' => true,
                'roles' => ['?'],
            ]
        ];
    }

    public function actions() {
        $captchaAction = [
            'class' => 'cii\captcha\CaptchaAction',
        ];

        return [
            'captcha' => [
                'class' => 'cii\captcha\CaptchaAction',
                'url' => Yii::$app->seo->relativeAdminRoute('captcha')
            ],
            /*
            'doc'=>[
                'class'=>'yii\web\ViewAction',
                'viewPrefix' => 'doc'
            ],*/
        ];
    }

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionPackage($name) {
        $pkg = Yii::$app->cii->package->getReflection($name);
    	if(!$pkg) {
    		throw new \Exception("The package " . $name . " could not be found");
    	}
        
        return Yii::$app->runAction('cii/package/view', ['id' => $pkg->getInstalledVersion()->package->id]);
    }

    public function actionApplication() {
        return $this->render('application');
    }

    public function actionCreatebackup() {
        $dumper = DbDumper::getInstance(Yii::$app->db);
        if($dumper->exportToFile(Yii::$app->runtimePath . '/database.sql')) {
            if(FileHelper::compressDirectory(
                Yii::$app->basePath,
                Yii::$app->basePath . '/web/backup.zip',
                [
                    'excludeDirectories' => [Yii::$app->basePath . '/.git'],
                    'excludeFiles' => [Yii::$app->basePath . '/web/backup.zip'],
                ])) {
                Yii::$app->session->setFlash('success', Yii::p('cii', 'Created new backup'));
            }else {
                Yii::$app->session->setFlash('error', Yii::p('cii', 'Could not compress files'));
            }
        }else {
            Yii::$app->session->setFlash('error', Yii::p('cii', 'Could not export database'));
        }
        
        return $this->goBackToReferrer();
    }

    public function actionLog() {
    	$logs = [];
    	foreach(Yii::$app->log->targets as $log) {
    		if($log instanceof FileTarget && $log->enabled) {
                $logs[] = [
    				'name' => basename($log->logFile),
    				'content' => @file_get_contents($log->logFile)
    			];
    		}
    	}

        return $this->render('log', [
        	'logs' => $logs
        ]);
    }

    public function actionFlushthumbnail() {
        Yii::$app->cii->flushThumbnails();
        return $this->goBackToReferrer();
    }

    public function actionFlushlog() {
    	foreach(Yii::$app->log->targets as $log) {
    		if($log instanceof FileTarget) {
    			@unlink($log->logFile);
    		}
    	}
        
    	return $this->goBackToReferrer();
    }

    public function actionFlushcache() {
        Yii::$app->cache->flush();
        return $this->goBackToReferrer();
    }

    public function actionFlushroutestatistics() {
        Yii::$app->cii->route->flushStatistics();
        return $this->goBackToReferrer();
    }

    public function actionLogin() {
        $model = new LoginForm();
        
        if($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('success', 'You have been logged in successfully');
            return $this->redirect([Yii::$app->seo->relativeAdminRoute('index')]);
        }

        return $this->render('login', [
            'model' => $model
        ]);
    }


    public function actionLogout() {
        Yii::$app->user->logout();
        Yii::$app->session->setFlash('success', Yii::p('cii', 'You have been logged out successfully'));
        return $this->goHome();
    }
}
