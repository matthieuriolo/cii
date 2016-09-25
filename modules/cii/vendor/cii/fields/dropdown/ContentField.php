<?php
namespace cii\fields\dropdown;

use Yii;
use app\modules\cii\models\Content;

class ContentField extends RelationField {
	public $baseUri = 'cii/content/view';
	
	public function findModel($pk) {
    	return Content::findOne($pk);
    }

	public function getValues() {
        return Yii::$app->cii->layout->getContentsForDropdown();
    }
}
