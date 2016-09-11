<?php


namespace app\modules\cii\models;

use Yii;
use yii\base\Model;

class RenameFileForm extends Model {
    public $original;
    public $name;

    public function attributeLabels() {
        return [
            'name'   => Yii::p('cii', 'Renamed name'),
        ];
    }

    public function rules() {
        return [
            [['original', 'name'], 'required'],
            [['original', 'name'], 'string', 'max' => 255],
        ];
    }
}
