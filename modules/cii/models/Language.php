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
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
            'shortCode' => Yii::t('app', 'Short Code'),
            'enabled' => Yii::t('app', 'Enabled'),

            'date' => Yii::t('app', 'Date format'),
            'time' => Yii::t('app', 'Time format'),
            'datetime' => Yii::t('app', 'Datetime format'),

            'decimalSeparator' => Yii::t('app', 'Decimal separator'),
            'thousandSeparator' => Yii::t('app', 'Thousand separator'),
            'decimals' => Yii::t('app', 'Decimals digits'),
            'removeZeros' => Yii::t('app', 'Remove tailing zeros'),
            
            'currencySymbol' => Yii::t('app', 'Currency symbol'),
            'currencySymbolPlace' => Yii::t('app', 'Symbol as suffix'),
            'currencySmallestUnit' => Yii::t('app', 'Smallest unit'),
            'currencyRemoveZeros' => Yii::t('app', 'Remove tailing zeros'),
            
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
