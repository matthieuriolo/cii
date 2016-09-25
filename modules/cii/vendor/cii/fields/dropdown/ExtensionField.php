<?php
namespace cii\fields\dropdown;

use Yii;
use app\modules\cii\models\Extension;

class ExtensionField extends RelationField {
	public $baseUri = 'cii/extension/view';
	
	public function findModel($pk) {
    	return Extension::findOne($pk);
    }

	public function getValues() {
        return null;
    }
}
