<?php


namespace app\modules\cii\models;

use Yii;
use yii\base\Model;

class UploadExtensionForm extends Model {
    public $file;

    public function attributeLabels() {
        return [
            'file'   => Yii::p('cii', 'Uploaded file'),
        ];
    }

    public function rules() {
        return [
            [['file'], 'file'],
        ];
    }
}
