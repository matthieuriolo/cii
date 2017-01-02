<?php 

namespace app\modules\cii\widgets;

use Yii;
use yii\base\Widget;
use cii\helpers\Html;

class Panel extends Widget {
    public $title;
    public $encodeTitle = true;
	public $content;
    public $buttons = [];


    protected function preRun($additionalHeaderContent = null) {
        echo '<div class="panel panel-default tabbed-panel">';

        echo '<div class="panel-heading">';
        
        if(is_array($this->buttons)) {
            echo '<div class="pull-right btn-group btn-group-xs">';
            foreach($this->buttons as $value) {
                echo Html::a($value['label'], $value['url'], ['class' => 'btn btn-primary']);
            }
            
            echo '</div>';
        }

        if($this->title) {
            echo '<span class="title">';
            
            echo $this->encodeTitle ? Html::encode($this->title) : $this->title;

            echo '</span>&nbsp;';
        }

        if($additionalHeaderContent) {
            echo $additionalHeaderContent;
        }

        echo '</div>';
            
        echo '<div class="panel-body no-padding">';

        echo $this->content;
    }

    protected function postRun() {
        echo '</div>';
        echo '</div>';
    }

    public function run() {
        $this->preRun();
        $this->postRun();
    }
}