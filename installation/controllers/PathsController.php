<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\helpers\CiiPathHelper;

class PathsController extends Controller {
    public function actionIndex() {
        $helper = new CiiPathHelper();
        $requirements = array(
		    array(
		        'path' => $helper->installationBase() . '../modules',
		        'isWritable' => true
		    ),

		    array(
		        'path' => $helper->installationBase() . '../layouts',
		        'isWritable' => true
		    ),

		    array(
		        'path' => $helper->installationBase() . '../messages',
		        'isWritable' => true
		    ),

		    array(
		        'path' => $helper->installationBase() . '../favicon.ico',
		        'isWritable' => true,
		        'isFile' => true
		    ),
		    
		    array(
		        'path' => $helper->installationBase() . '../runtime',
		        'isWritable' => true
		    ),

		    array(
		        'path' => $helper->installationBase() . '../assets',
		        'isWritable' => true
		    ),

		    array(
		        'path' => $helper->installationBase() . '../thumbnails',
		        'isWritable' => true
		    ),

		    array(
		        'path' => $helper->installationBase() . '../config/db.php',
		        'isWritable' => true,
		        'isFile' => true
		    ),

		    array(
		        'path' => $helper->installationBase() . '../config/web.php',
		        'isWritable' => true,
		        'isFile' => true
		    ),

		    array(
		        'path' => $helper->installationBase() . '../uploads',
		        'isWritable' => true,
		        'isFile' => false
		    ),
		);
		$ret = $helper->check($requirements)->result;
		return $this->render('index', $ret);
    }


    public function actionAfter() {
    	$helper = new CiiPathHelper();
  		$requirements = array(
		    array(
		        'path' => $helper->installationBase() . '../config/db.php',
		        'isWritable' => false,
		        'isFile' => true
		    ),

		    array(
		        'path' => $helper->installationBase() . '../config/web.php',
		        'isWritable' => false,
		        'isFile' => true
		    ),
		);
    	$ret = $helper->check($requirements)->result;
    	$f = $helper->installationBase() . '../config/web.php';

    	$ret['cookieValidationKey'] = false;
    	if(is_file($f)) {
    		if(is_writable($f)) {
    			$helper->replaceParameterInFile($f, 'cookieValidationKey', Yii::$app->getSecurity()->generateRandomString());
    		}

    		if($c = file_get_contents($f)) {
    			if(strpos($c, "'cookieValidationKey' => ''") === false) {
    				$ret['cookieValidationKey'] = true;
    			}
    		}
    	}

		return $this->render('after', $ret);
    }
}
