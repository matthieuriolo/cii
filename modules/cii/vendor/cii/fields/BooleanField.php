<?php
namespace cii\fields;

use Yii;
use cii\widgets\Toggler;

class BooleanField extends AbstractField {
	public function getView($model) {
		return Yii::$app->formatter->asBoolean($this->getRaw($model));
    }

	public function getEditable($model, $form) {
        return Toggler::widget([
            'model' => $model,
            'property' => $this->attribute,
            'form' => $form
        ]);
    }
}
