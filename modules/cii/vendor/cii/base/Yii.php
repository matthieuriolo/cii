<?php
/**
 * Yii bootstrap file.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


$path = __DIR__ . '/../../yiisoft/yii2';

require($path . '/BaseYii.php');

class Yii extends \yii\BaseYii {
	public static function t($category, $message, $params = [], $language = null) {
		if($category === 'yii') {
			return static::p('cii', $message, $params, $language);
		}

		return parent::t($category, $message, $params, $language);
	}

	
	public static function p($category, $message, $params = [], $language = null) {
		return parent::t(['package', $category], $message, $params, $language);
	}

	public static function l($category, $message, $params = [], $language = null) {
		return parent::t(['layout', $category], $message, $params, $language);
	}
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = require($path . '/classes.php');
Yii::$container = new yii\di\Container();
