<?php
namespace cii\fields\dropdown;

use Yii;

class ContentField extends RelationField {
	public $baseUri = 'cii/content/view';
	public function getValues() {
        return Yii::$app->cii->layout->getContentsForDropdown();
    }
}
