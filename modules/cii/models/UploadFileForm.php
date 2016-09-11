<?php


namespace app\modules\cii\models;

use Yii;
use yii\base\Model;

class UploadFileForm extends Model {
    public $files;

    public function attributeLabels() {
        return [
            'files'   => Yii::p('cii', 'Uploaded files'),
        ];
    }

    public function rules() {
        return [
            [['files'], 'file','skipOnEmpty' => false],
        ];
    }
}
