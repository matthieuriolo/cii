<?php

namespace app\modules\cii\controllers;

use Yii;
use cii\backend\BackendController as Controller;
use app\modules\cii\models\Package as Core_Module;
use app\modules\cii\models\Configuration as Core_Settings;
use app\modules\cii\Permission;

use yii\log\FileTarget;

use yii\data\ArrayDataProvider;

class BackendController extends Controller {
    public function getAccessRoles() {
        return [Permission::MANAGE_LOG, Permission::MANAGE_ADMIN];
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
}
