<?php

namespace cii\base;

use Yii;
use yii\base\InvalidConfigException;
use cii\base\Model;

class Configuration extends Model {
	public $key;
	public $label;
	public $id;
	public $default = null;
	public $type = 'text';
	public $values = null;
    
	public function init() {
		if(!$this->key || !$this->id) {
			throw new InvalidConfigException();
		}

		if(!$this->label) {
			$this->label = ucfirst($this->key);
		}

		parent::init();
	}

	public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'Extension'),
            'label' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'default' => Yii::t('app', 'Default'),
            'value' => Yii::t('app', 'Value'),
        ];
    }

    public function getPreparedDefault() {
    	switch($this->type) {
    		case 'password':
    		case 'image':
    		case 'text':
    		case 'in':
    		case 'email':
    			return Yii::$app->formatter->asText($this->default);
    		case 'boolean':
    			return Yii::$app->formatter->asBoolean($this->default);
    		case 'float':
    			return Yii::$app->formatter->asDecimals($this->default);
    		case 'integer':
    			return Yii::$app->formatter->asInteger($this->default);
    		default:
    			throw new InvalidConfigException();
    	}
    }
    
    protected function getValues() {
        $module = Yii::$app->getModule($this->id);
        $types = $module->getSettingTypes();
        if(!isset($types[$this->key], $types[$this->key]['values'])) {
            return [];
        }

        return $types[$this->key]['values'];
    }

	public function getValue() {
		$val = Yii::$app->cii->setting($this->id, $this->key, null);
		//protected passwords
		if($this->type == 'password') {
			return str_pad('', strlen($val) * 3, '●');
		}else if($this->type == 'in') {
            $values = $this->getValues();
            return isset($values[$val]) ? $values[$val] : null;
        }

		return $val;
	}
}