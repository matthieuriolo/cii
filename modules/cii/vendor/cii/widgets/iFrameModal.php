<?php
namespace cii\widgets;

use Yii;
use yii\bootstrap\Modal;
use yii\base\InvalidConfigException;
use cii\helpers\Html;

class iFrameModal extends Modal {
    public $src;

    public function init() {
        if(!$this->src) {
            throw new InvalidConfigException('The property src has to be set');
        }

        if(!$this->size) {
            $this->size = 'modal-full';
        }

        $this->size .= ' modal-iframe';

        return parent::init();
    }


    public function run() {
        echo Html::tag('iframe', '', [
            'src' => $this->src . (strpos($this->src, '?') ? '&' : '?') . 'iframe=' . $this->id,
            'frameborder' => 0,
            'class' => 'full'
        ]);
        return parent::run();
    }
}
