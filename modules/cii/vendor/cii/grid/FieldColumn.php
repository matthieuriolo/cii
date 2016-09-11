<?php
namespace cii\grid;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;

class FieldColumn extends \yii\grid\DataColumn {
	protected function renderDataCellContent($model, $key, $index) {
        if ($this->content === null) {

            return Yii::$app->cii->createFieldObject($this->format, [
                'value' => $this->getDataCellValue($model, $key, $index)
            ])->getView($model);
            //$this->grid->formatter->format($this->getDataCellValue($model, $key, $index), $this->format);
        } else {
            return parent::renderDataCellContent($model, $key, $index);
        }
    }
}
