<?php 

namespace app\modules\cii\widgets;

use Yii;
use yii\helpers\Json;
use yii\base\Widget;
use cii\helpers\Html;

use app\layouts\cii\assets\FlotAsset;

class PieFlot extends Widget {
    public $segments = [];


    public function run() {
        $view = $this->getView();
        $id = 'flot-' . $this->id;
        
        FlotAsset::register($view);
        echo Html::tag('div', '', [
            'id' => $id,
            'class' => 'flot-chart',
        ]);

        $view->registerJs('
            var plot = $.plot("#' . $id . '", ' . Json::encode($this->segments) . ', {
                series: {
                    pie: {
                        show: true
                    }
                },
                legend: {
                    show: false
                }
            });
        ');
    }
}