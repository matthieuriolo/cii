<?php
namespace cii\fields;

use Yii;
use cii\widgets\PjaxModal;
use cii\helpers\Url;
use cii\helpers\Html;

abstract class PjaxObjectField extends PjaxField {
    abstract protected function fetchModel($id);

     public function getView($model) {
        if($obj = $this->getRaw($model)) {
            if($url = $this->getViewUrl($model, $obj)) {
                $name = $this->viewNameAttribute;
                return Html::a($obj->$name, $url, [
                    'target' => '_blank',
                    'data-pjax' => 0,
                ]);
            }

            $name = $this->viewNameAttribute;
            return Yii::$app->formatter->asText($obj->$name);
        }

        return Yii::$app->formatter->asText(null);
    }
    public function getRaw($model) {
        $value = parent::getRaw($model);
        if($value && !is_object($value)) {
            return $this->fetchModel($value);
        }

        return $value;
    }
}
