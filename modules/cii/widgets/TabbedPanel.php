<?php 

namespace app\modules\cii\widgets;

use Yii;
use cii\helpers\Html;
use yii\bootstrap\Tabs;

class TabbedPanel extends Panel {
    public $items = [];


    public function run() {
        $options = [];
        foreach($this->items as $key => $value) {
            $options[] = $value['label'];
        }


        $this->preRun(
            Html::dropDownList($this->id . '-selector', null, $options, ['data-controller' => 'tabbed-panel-selector'])
        );
        
        echo Tabs::widget([
            'id' => $this->id,
            'items' => $this->items
        ]);

        $this->postRun();
    }
}