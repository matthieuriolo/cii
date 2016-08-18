<?php
namespace cii\grid;

use Yii;
use yii\base\InvalidConfigException;

class ActionColumn extends \yii\grid\ActionColumn {
	public $relativeRoute = 'app\modules\cii\routes\Backend';
	public $appendixRoute;


	public function init() {
		if($this->appendixRoute === null) {
			throw new InvalidConfigException("The property appendixRoute must be set in cii\\grid\\ActionColumn");
		}

        parent::init();
    }

    public function createUrl($action, $model, $key, $index) {
        if (is_callable($this->urlCreator)) {
            return call_user_func($this->urlCreator, $action, $model, $key, $index);
        } else {
           $params = is_array($key) ? $key : ['id' => (string) $key];
           $route = [\Yii::$app->seo->relativeRoute($this->relativeRoute, $this->appendixRoute . '/' . $action), $params];

           return Yii::$app->urlManager->createUrl($route);
        }
    }
}
