<?php

namespace cii\base;

use Yii;
use yii\base\InvalidConfigException;
use cii\base\Model;

class Configuration extends Model {
	public $key;
	public $label;
	public $id;
	public $default;
	public $type = 'text';
	public $values;
    public $extension_type;
    protected $types;

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
    	return $this->prepareValue($this->default);
    }

    protected function prepareValue($val) {
        switch($this->type) {
            case 'image':
            case 'text':
            case 'email':
            default:
                return Yii::$app->formatter->asText($val);
            case 'in':
                $values = $this->getValues();
                return isset($values[$val]) ? $values[$val] : null;
            case 'password':
                if(empty($val)) {
                    return null;
                }

                return Yii::$app->formatter->asText(str_pad('', strlen($val) * 3, '●'));
            case 'boolean':
                return Yii::$app->formatter->asBoolean($val);
            case 'float':
                return Yii::$app->formatter->asDecimals($val);
            case 'integer':
                return Yii::$app->formatter->asInteger($val);
            case 'color':
                if($val) {
                    return '<span class="color-block" style="background-color: ' . $val .';"></span> ' . Yii::$app->formatter->asText($val);
                }
                return null;
        }
    }
    
    protected function getValues() {
        $types = $this->getSettingTypes();

        if(!isset($types[$this->key], $types[$this->key]['values'])) {
            return [];
        }

        return $types[$this->key]['values'];
    }

    protected function getSettingTypes() {
        if(!$this->types) {
            $ext = $this->extension_type;
            $ext = Yii::$app->cii->$ext;
            $this->types = $ext->getSettingTypes($this->id);
        }

        return $this->types;
    }

	public function getValue() {
        return $this->prepareValue(Yii::$app->cii->setting($this->extension_type, $this->id, $this->key, null));
	}
}