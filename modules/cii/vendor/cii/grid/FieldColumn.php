<?php
namespace cii\grid;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;

class FieldColumn extends \yii\grid\DataColumn {
	protected function renderDataCellContent($model, $key, $index) {
        if($this->content === null) {

            $field = Yii::$app->cii->createFieldObject($this->format, [
                'value' => $this->getDataCellValue($model, $key, $index)
            ]);
            
            if($field) {
                return $field->getView($model);
            }
        }

        return parent::renderDataCellContent($model, $key, $index);
    }
}
