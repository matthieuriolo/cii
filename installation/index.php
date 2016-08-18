<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('YII_ENV_DEV') or define('YII_ENV_DEV', 'dev');

$switch = 'requirements';
if(isset($_GET['r'])) {
    $switch = $_GET['r'];
}


if($switch == 'requirements') {
	require 'requirements.php';
}else {
	$vendorPath = __DIR__ . '/../modules/cii/vendor/';
	require($vendorPath . 'autoload.php');
	require($vendorPath . 'yiisoft/yii2/Yii.php');
	require(__DIR__ . '/../modules/cii/vendor/cii/web/Application.php');
	
	$config = require(__DIR__ . '/config/web.php');
	
	(new cii\web\Application($config))->run();
}
