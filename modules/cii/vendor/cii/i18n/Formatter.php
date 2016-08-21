<?php
namespace cii\i18n;

use Yii;

class Formatter extends \yii\i18n\Formatter {
    public $removeZeros = false;
    public $currencyCodePlace = false;
    public $currencyRemoveZeros = false;
    public $currencySmallestUnit = 0.01;
    public $decimals = 2;

    public $dateFormat = 'php:m/d/Y';
    public $timeFormat = 'php:H:i:s';
    public $datetimeFormat = 'php:m/d/Y H:i:s';


    public function initValuesFromLanguage($language) {
        if($language->date) {
            $this->dateFormat = 'php:' . $language->date;
        }

        if($language->time) {
            $this->timeFormat = 'php:' . $language->time;
        }

        if($language->datetime) {
            $this->datetimeFormat = 'php:' . $language->datetime;
        }
        

        $this->decimalSeparator = $language->decimalSeparator;
        $this->thousandSeparator = $language->thousandSeparator;
        
        $this->decimals = $language->decimals;
        $this->removeZeros = $language->removeZeros;
        

        $this->currencyCode = $language->currencySymbol;
        $this->currencyCodePlace = $language->currencySymbolPlace;
        $this->currencyRemoveZeros = $language->currencyRemoveZeros;
    }

	public function init() {
        /* turn off support for the intl extension */
        if ($this->timeZone === null) {
            $this->timeZone = Yii::$app->timeZone;
        }

        if ($this->locale === null) {
            $this->locale = Yii::$app->language;
        }

        if ($this->booleanFormat === null) {
            $this->booleanFormat = [
                '<i class="glyphicon glyphicon-remove text-danger" alt="no"></i>',
                '<i class="glyphicon glyphicon-ok text-success" alt="yes"></i>',
            ];
        }

        if ($this->nullDisplay === null) {
            $this->nullDisplay = '<span class="not-set">' . Yii::t('yii', '(not set)', [], $this->locale) . '</span>';
        }

        if($this->currencyCode == null) {
            $this->currencyCode = '$ ';
        }
    }


    public function asCurrency($value, $currency = null, $options = [], $textOptions = []) {
        if ($value === null) {
            return $this->nullDisplay;
        }

        if($currency === null) {
            $currency = (string)$this->currencyCode;
        }

        $value = $this->normalizeNumericValue($value);

        $decimals = null;
        if($this->currencyRemoveZeros && (int)$value == $value) {
            $decimals = 0;
        }

        //round to smallest unit
        if($this->currencySmallestUnit !== null) {
            if($value >= 0) {
                $value = ceil($value / $this->currencySmallestUnit) * $this->currencySmallestUnit;
            }else {
                $value = ceil($value / $this->currencySmallestUnit) * $this->currencySmallestUnit;
            }
        }

        $value = $this->asDecimal($value, $decimals, $options, $textOptions);
        return $this->currencyCodePlace ? $value . $currency : $currency . $value;
    }

    public function asDecimal($value, $decimals = null, $options = [], $textOptions = []) {
        if($value === null) {
            return $this->nullDisplay;
        }

        $value = $this->normalizeNumericValue($value);
        
        if($decimals === null) {
            $decimals = $this->decimals;
        }

        return number_format($value, $decimals, $this->decimalSeparator, $this->thousandSeparator);
    }
}
