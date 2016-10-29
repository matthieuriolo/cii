<?php
namespace cii\widgets;

use Yii;
use yii\bootstrap\Modal;
use yii\base\InvalidConfigException;

use cii\widgets\Pjax;
use cii\helpers\Html;
use cii\helpers\Url;


class PjaxModal extends Modal {
    public $pjaxid;
    public $callbackSubmit;
    public $clientOptions = ['backdrop' => 'static', 'keyboard' => false];

    public function init() {
        if(!$this->pjaxid) {
            $this->pjaxid = Yii::$app->request->pjaxid();
        }

        if(!$this->header) {
            $this->header = Yii::p('cii', 'Select an item');
        }
        
        $this->size = 'modal-pjax modal-full';

        parent::init();
    }

    public function run() {
        echo Html::hiddenInput('pjax_modal_field', null, [
            'id' => $this->id . '_field'
        ]);

        echo Html::hiddenInput('pjax_modal_url', null, [
            'id' => $this->id . '_url'
        ]);

        echo Html::hiddenInput('pjax_modal_name', null, [
            'id' => $this->id . '_name'
        ]);

        Pjax::begin([
            'id' => $this->pjaxid,
            'enablePushState' => false,
            'enableReplaceState' => false,
            'timeout' => 5000,

            'beforePjax' => "jQuery('#" . $this->pjaxid . "').on('pjax:beforeSend', function() {
                \$('#" . $this->id . "_submit').addClass('disabled');
                \$('#" . $this->id . "_name').val('');
                \$('#" . $this->id . "_url').val('');
                \$('#" . $this->id . "_field').val('');
            });"
        ]);
        echo '<div class="text-center"><i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom"></i></div>';
        Pjax::end();
        
        echo $this->getButtons();
        
        return parent::run();
    }

    public function getButtons() {
        return '<div class="form-group buttons">'
            . Html::a(
                Yii::p('cii', 'Cancel'),
                '#',
                [
                    'onclick' => '(function(evt) {
                        evt.preventDefault();
                        $(\'#' . $this->id . '\').modal(\'hide\');
                    })(event)',
                    'class' => 'btn btn-warning'
                ]
            )
            . '&nbsp;'
            . Html::a(Yii::p('cii', 'Select'), '#', [
                'class' => 'btn btn-primary disabled',
                'id' => $this->id . '_submit',
                'onclick' => '(function(evt) {
                    evt.preventDefault();
                    $(\'#' . $this->id . '\').modal(\'hide\');
                    ' . ($this->callbackSubmit ? $this->callbackSubmit : '') . '
                })(event);'
            ])
        . '</div>';
    }

    protected function initOptions() {
        parent::initOptions();
        unset($this->closeButton['data-dismiss']);
        $this->closeButton['onclick'] = '(function(evt) { $(\'#' . $this->id . '\').modal(\'hide\'); })(event)';
    }
}
