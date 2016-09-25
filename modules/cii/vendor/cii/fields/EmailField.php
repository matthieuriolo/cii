<?php
namespace cii\fields;

use Yii;

class EmailField extends TextField {
	public function rules() {
		return [
			[['value'], 'email']
		];
	}
	
	public function getView($model) {
        return Yii::$app->formatter->asEmail($this->getRaw($model));
    }
}
