<?php
namespace cii\web;

use Yii;
use app\modules\core\models\Core_Content;

class View extends \yii\web\View {
	public function getContents($name = null) {
		return Yii::$app->cii->layout->getContents($name);
	}
}
