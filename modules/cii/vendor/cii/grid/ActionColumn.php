<?php
namespace cii\grid;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;

class ActionColumn extends \yii\grid\ActionColumn {
	public $relativeRoute = 'app\modules\cii\routes\Backend';
	public $appendixRoute;
    public $optionsRoute;

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
           if(is_array($this->optionsRoute)) {
            $params = $params + $this->optionsRoute;
           }

           $route = [\Yii::$app->seo->relativeRoute($this->relativeRoute, $this->appendixRoute . '/' . $action), $params];

           return Yii::$app->urlManager->createUrl($route);
        }
    }

    protected function initDefaultButtons() {
      parent::initDefaultButtons();
       if(!isset($this->buttons['enable'])) {
            $this->buttons['enable'] = function ($url, $model, $key) {
                 $options = array_merge([
                    'title' => Yii::t('yii', 'Enable'),
                    'aria-label' => Yii::t('yii', 'Enable'),
                    //'data-method' => 'post',
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                return Html::a('<span class="glyphicon glyphicon-floppy-saved"></span>', $url, $options);
            };
        }

       if(!isset($this->buttons['disable'])) {
            $this->buttons['disable'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Disable'),
                    'aria-label' => Yii::t('yii', 'Disable'),
                    //'data-method' => 'post',
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                return Html::a('<span class="glyphicon glyphicon-floppy-remove"></span>', $url, $options);
            };
        }

        if(!isset($this->buttons['up'])) {
            $this->buttons['up'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Order up'),
                    'aria-label' => Yii::t('yii', 'Order up'),
                    //'data-method' => 'post',
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                return Html::a('<span class="glyphicon glyphicon-arrow-up"></span>', $url, $options);
            };
        }

        if(!isset($this->buttons['down'])) {
            $this->buttons['down'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Order down'),
                    'aria-label' => Yii::t('yii', 'Order down'),
                    //'data-method' => 'post',
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                return Html::a('<span class="glyphicon glyphicon-arrow-down"></span>', $url, $options);
            };
        }
    }
}
