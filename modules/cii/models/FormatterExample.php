<?php


namespace app\modules\cii\models;

use Yii;
use yii\base\Model;

class FormatterExample extends Model {
    public $numeric;
    protected $timestamp;
    protected $formatter;

    public function init() {
        $this->formatter = Yii::$app->formatter;
        $this->timestamp = time();
    }

    public function attributeLabels() {
        return [
            'numeric' => Yii::p('cii', 'Integer or float')
        ];
    }

    public function rules() {
        return [
            [['numeric'], 'number'],
        ];
    }

    public function setLanguage($language) {
        $formatter = clone $this->formatter;
        $formatter->initValuesFromLanguage($language);
        $this->formatter = $formatter;
    }

    public function getDate() {
        return $this->formatter->asDate($this->timestamp);
    }

    public function getTime() {
        return $this->formatter->asTime($this->timestamp);
    }

    public function getDatetime() {
        return $this->formatter->asDatetime($this->timestamp);
    }

    public function getInteger() {
        return $this->formatter->asInteger($this->getNumber());
    }

    public function getFloat() {
        return $this->formatter->asDecimal($this->getNumber());
    }

    public function getCurrency() {
        return $this->formatter->asCurrency($this->getNumber());
    }

    protected function getNumber() {
        if(!$this->validate() || is_null($this->numeric)) {
            return 12345.6789;
        }

        return $this->numeric;
    }
}
