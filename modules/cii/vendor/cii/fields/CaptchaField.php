<?php
namespace cii\fields;

use Yii;
use yii\captcha\Captcha;

class CaptchaField extends AbstractField {
	public function getView($model) {
		return '<span class="not-set">' . Yii::p('cii', 'Captcha field') . '</span>';
	}

	public function getEditable($model, $form) {
		return $form->field($model, 'captcha')->widget(Captcha::classname(), [
			'captchaAction' => '//' . $model->captchaRoute->getBreadcrumbs(),
			'template' => '<div class="row"><div class="col-md-3" title="Reload image">{image}</div><div class="col-md-9">{input}</div></div>',
		]);
    }
}
