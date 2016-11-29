<?php

if(is_dir('installation')) {
	header('Location: installation');
	header('X-Redirect: installation');
}else {
	// comment out the following two lines when deployed to production
	defined('YII_DEBUG') or define('YII_DEBUG', true);
	defined('YII_ENV') or define('YII_ENV', 'dev');

	require(__DIR__ . '/modules/cii/vendor/autoload.php');
	//require(__DIR__ . '/../modules/cii/vendor/yiisoft/yii2/Yii.php');
	require(__DIR__ . '/modules/cii/vendor/cii/base/Yii.php');

	$config = require(__DIR__ . '/config/web.php');

	require(__DIR__ . '/modules/cii/vendor/cii/web/Application.php');

	(new cii\web\Application($config))->run();
}