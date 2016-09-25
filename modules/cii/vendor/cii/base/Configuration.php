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
            'translatedType' => Yii::t('app', 'Type'),
            'default' => Yii::t('app', 'Default'),
            'value' => Yii::t('app', 'Value'),
        ];
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
    
    protected function displayFieldValue($value) {
        $config = ['value' => $value];
        if($this->type == 'in') {
            $config['values'] = $this->getValues();
        }

        return Yii::$app->cii->createFieldObject($this->type, $config)->getView($this);
    }
    
    public function getTranslatedType() {
        return Yii::p('cii', ucfirst($this->type));
    }

    public function getValue() {
        return $this->displayFieldValue(
            Yii::$app->cii->setting($this->extension_type, $this->id, $this->key, null)
        );
    }

    public function getPreparedDefault() {
        return $this->displayFieldValue($this->default);
    }

    public function getExtension() {
        $ext = 'app\modules\cii\models\\'. ucfirst($this->extension_type);
        return $ext::find()->joinWith('extension as ext')->where(['ext.name' => $this->id])->one();
    }
}