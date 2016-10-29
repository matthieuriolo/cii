<?php
namespace cii\widgets;

use Yii;
use yii\helpers\Json;

class Pjax extends \yii\widgets\Pjax {
    public $timeout = 5000;
    public $beforePjax;

    public function registerClientScript()
    {
        $id = $this->options['id'];
        $this->clientOptions['push'] = $this->enablePushState;
        $this->clientOptions['replace'] = $this->enableReplaceState;
        $this->clientOptions['timeout'] = $this->timeout;
        $this->clientOptions['scrollTo'] = $this->scrollTo;
        $options = Json::htmlEncode($this->clientOptions);
        $js = '';
        if ($this->linkSelector !== false) {
            $linkSelector = Json::htmlEncode($this->linkSelector !== null ? $this->linkSelector : '#' . $id . ' a');
            if($this->beforePjax) {
                $js .= $this->beforePjax;
            }
            $js .= "jQuery(document).pjax($linkSelector, \"#$id\", $options);";
        }

        if ($this->formSelector !== false) {
            $formSelector = Json::htmlEncode($this->formSelector !== null ? $this->formSelector : 'form[data-pjax]');
            $submitEvent = Json::htmlEncode($this->submitEvent);
            $js .= "\njQuery('#" . $id . "').on($submitEvent, $formSelector, function (event) {jQuery.pjax.submit(event, '#$id', $options);});";
            $js .= "\njQuery('#" . $id . "').on('pjax:end', function() { App.loadControllersFromDOM(jQuery('#" . $id . "')); });";
        }

        $view = $this->getView();
        PjaxAsset::register($view);

        if ($js !== '') {
            $view->registerJs($js);
        }
    }
}
