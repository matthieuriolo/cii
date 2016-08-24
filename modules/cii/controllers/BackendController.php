<?php

namespace app\modules\cii\controllers;

use Yii;
use cii\backend\BackendController as Controller;
use app\modules\cii\models\Package as Core_Module;
use app\modules\cii\models\Configuration as Core_Settings;
use yii\log\FileTarget;

use yii\data\ArrayDataProvider;

class BackendController extends Controller {
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionPackage($name) {
        $pkg = Yii::$app->cii->package->getReflection($name);
    	if(!$pkg) {
    		throw new \Exception("The package " . $name . " could not be found");
    	}
        
        return Yii::$app->runAction('cii/package/view', ['id' => $pkg->getInstalledVersion()->id]);
    }

    public function actionLog() {
    	$logs = [];
    	foreach(Yii::$app->log->targets as $log) {
    		if($log instanceof FileTarget) {
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

    public function actionFlushlog() {
    	foreach(Yii::$app->log->targets as $log) {
    		if($log instanceof FileTarget) {
    			@unlink($log->logFile);
    		}
    	}
    	
    	$this->redirect([Yii::$app->seo->relativeAdminRoute('log')]);
    }
}
