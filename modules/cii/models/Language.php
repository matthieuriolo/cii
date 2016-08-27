<?php

namespace app\modules\cii\models;

use Yii;
use DateTime;

class Language extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%Cii_Language}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'code', 'enabled'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['shortcode'], 'string', 'max' => 2],
            [['code'], 'string', 'max' => 6],
            [['code'], 'unique'],
            [['enabled'], 'boolean'],

            [['removeZeros', 'currencyRemoveZeros', 'currencySymbolPlace'], 'boolean'],
            [['currencySymbol', 'thousandSeparator', 'decimalSeparator', 'time'], 'string', 'max' => 8],
            [['datetime', 'date'], 'string', 'max' => 12],
            [['currencySmallestUnit'], 'number', 'min' => 0.000001],
            [['decimals'], 'number', 'min' => 0, 'integerOnly' => true],

            [['datetime', 'time', 'date'], 'validatePHPFormat'],
        ];
    }

    public function validatePHPFormat($attribute, $params) {
        $val = $this->$attribute;
        if(@date($val) === false) {
            $this->addError($attribute, 'Incorrect format');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => Yii::p('cii', 'Name'),
            'code' => Yii::p('cii', 'Code'),
            'shortCode' => Yii::p('cii', 'Short Code'),
            'enabled' => Yii::p('cii', 'Enabled'),

            'date' => Yii::p('cii', 'Date format'),
            'time' => Yii::p('cii', 'Time format'),
            'datetime' => Yii::p('cii', 'Datetime format'),

            'decimalSeparator' => Yii::p('cii', 'Decimal separator'),
            'thousandSeparator' => Yii::p('cii', 'Thousand separator'),
            'decimals' => Yii::p('cii', 'Decimals digits'),
            'removeZeros' => Yii::p('cii', 'Remove tailing zeros'),
            
            'currencySymbol' => Yii::p('cii', 'Currency symbol'),
            'currencySymbolPlace' => Yii::p('cii', 'Symbol as suffix'),
            'currencySmallestUnit' => Yii::p('cii', 'Smallest unit'),
            'currencyRemoveZeros' => Yii::p('cii', 'Remove tailing zeros'),
            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentVisibilities() {
        return $this->hasMany(ContentVisibilities::className(), ['language_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes() {
        return $this->hasMany(Route::className(), ['language_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers() {
        return $this->hasMany(User::className(), ['language_id' => 'id']);
    }
}
