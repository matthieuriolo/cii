<?php
namespace cii\fields;

use Yii;
use cii\widgets\PjaxModal;
use cii\helpers\Url;
use cii\helpers\Html;

abstract class PjaxField extends AbstractField {
    protected $pjaxid;
    public $pjaxUrl;
    public $header;

    public $viewNameAttribute = 'name';

    public function init() {
        $this->pjaxid = uniqid();
        parent::init();
    }

    abstract protected function getPjaxUrl();
    protected function getViewUrl($model, $data) {
        return null;
    }

    public function getView($model) {
        $name = $this->viewNameAttribute;
        if($model->$name) {
            return Yii::$app->formatter->asText($model->$name);
        }
        return Yii::$app->formatter->asText(null);
    }

	public function getEditable($model, $form) {
        return PjaxModal::widget([
                'id' => $id = uniqid(),
                'pjaxid' => $this->pjaxid,
                'header' => $this->header,
                'callbackSubmit' => '
                    $("input[name=\"' . Html::getInputName($model, $this->attribute) . '\"]").val($("#' . $id . '_field").val());
                    var link = $("#' . $id . '_link");

                    link.text($("#' . $id . '_name").val());
                    if(data = $("#' . $id . '_url").val()) {
                        link.attr("href", data);
                        link.removeClass("disabled");
                    }
                '
            ])
            . $form->field($model, $this->attribute, [
                'template' => "{label}\n"
                . "<div class=\"input-group\">"
                . '<a id="' . $id . '_link" href="" class="form-control disabled" target="_blank" data-pjax="0">' . Yii::$app->formatter->asText(null) . '</a>'
                . "{input}"


                . "<span class=\"input-group-addon input-group-addons\">"
                . "<a class=\"glyphicon glyphicon glyphicon-remove\" onclick=\"(function() {"
                    . "\$('input[name=\'" . Html::getInputName($model, $this->attribute) . "\']').val('');"
                    . "\$('#" . $id . "_link').addClass('disabled').attr('href', '').text('');"
                . "})()\"></a>"
                . "</span>"

                . "<span class=\"input-group-addon\">"
                . "<a class=\"glyphicon glyphicon glyphicon-link\" onclick=\"(function() {
                    jQuery('#" . $this->pjaxid . "').html('<div class=\'vertical-align\'><div class=\'text-center vertical-align-inner\'><i class=\'fa fa-spinner fa-pulse fa-3x fa-fw\'></i></div></div>')
                    $.pjax({
                        url: '" . Url::toRoute($this->getPjaxUrl(), true) . "',
                        container: '#" . $this->pjaxid . "',
                        replaceRedirect: false,
                        pushRedirect: false,
                        replace: false,
                        history: false,
                        timeout: 5000,
                    });
                    \$('#" . $id . "').modal('show');
                    })()\"></a>"
                . "</span></div>\n{hint}\n{error}"
        ])->hiddenInput();
    }
}
