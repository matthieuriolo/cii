<?php
namespace cii\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;

class Toggler extends Widget {
    public $model;
    public $property;
    public $form;

    public function run() {
        $name = Html::getInputName($this->model, $this->property);
        $checked = $this->property;
        $checked = $this->model->$checked;

        echo '<div class="form-group">',
                Html::activeLabel($this->model, $this->property),
                '<div class="form-control-static">',
                    '<div class="toggler toggler-style-yes-no">',
                    //$this->form->field($this->model, $this->property)->checkbox(),
                    Html::hiddenInput($name, '0'),
                    Html::checkbox($name, $checked),
                        '<label for="', $name, '" class="toggler-separator"></label>',
                    '<div class="toggler-background"></div>',
                    '<span class="toggler-item"><span class="glyphicon glyphicon-remove"></span></span><span class="toggler-item"><span class="glyphicon glyphicon-ok"></span></span>',
                '</div>',
            '</div>',
        '</div>';
    }
}
