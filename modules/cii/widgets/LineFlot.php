<?php 

namespace app\modules\cii\widgets;

use Yii;
use yii\helpers\Json;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\web\JsExpression;

use cii\helpers\Html;
use app\layouts\cii\assets\FlotAsset;

class LineFlot extends Widget {
    public $lines = [];
    public $xaxis = 'date';
    public $yaxis = 'integer';

    public $axisDateFormat = "%d.%m";

    protected function getAxis($axis) {
        if(is_array($axis)) {
            return Json::encode($axis);
        }

        if($axis === 'float') {
            return Json::encode([
                    'min' => 0,
                    'minTickSize' => 1
                ]);
        }else if($axis === 'integer') {
            return Json::encode([
                    'min' => 0,
                    'minTickSize' => 1,
                    'tickFormatter' => new JsExpression('function(val, axis) {
                        return val.toFixed(0);
                    }')
                ]);
        }else if($axis === 'date') {
            return Json::encode([
                    'mode' => "time",
                    'timeformat' => $this->axisDateFormat,
                    'minTickSize' => [1, "day"]
                ]);
        }

        throw new InvalidConfigException('The axis is not valid');
    }

    public function run() {
        $view = $this->getView();
        $id = 'flot-' . $this->id;
        
        FlotAsset::register($view);
        echo Html::tag('div', '', [
            'id' => $id,
            'class' => 'flot-chart',
        ]);

        $lines = [];
        foreach($this->lines as $line) {
            $data = [];
            if(isset($line['label'])) {
                $data['label'] = $line['label'];
            }

            
            foreach($line['data'] as $x => $y) {
                $data['data'][] = [$x, $y];
            }

            $lines[] = $data;
        }




        $view->registerJs('
            var plot = $.plot("#' . $id . '", ' . Json::encode($lines) . ', {
                series: {
                    lines: {
                        show: true
                    },

                    points: {
                        show: true
                    }
                },

                grid: {
                    hoverable: true,
                    clickable: true
                },

                yaxis: ' . $this->getAxis($this->yaxis) . ',
                xaxis: ' . $this->getAxis($this->xaxis) . '
            });


            if(!$("#flot-tooltip").length) {
                $("<div id=\"flot-tooltip\"></div>").appendTo("body");
            }

            $("#' . $id . '").bind("plothover", function (event, pos, item) {
                if(item) {
                    $("#flot-tooltip").html("Total: " + item.datapoint[1])
                        .css({top: item.pageY + 5, left: item.pageX + 5})
                        .show();
                } else {
                    $("#flot-tooltip").hide();
                }
            });
        ');
    }
}