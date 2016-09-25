<?php

namespace app\modules\cii\models;

use Yii;
use yii\validators\Validator;
use yii\base\InvalidConfigException;

class Configuration extends \yii\db\ActiveRecord {
    protected $_types;
    public static function tableName() {
        return '{{%Cii_Configuration}}';
    }

    public function rules() {
        return [
            [['name'], 'required'],
            [['extension_id'], 'integer'],
            [['name', 'value'], 'string', 'max' => 255],
            [['value'], 'validateValue'],
            
            [['name'], 'unique', 'targetAttribute' => ['name', 'extension_id']],
            [['extension_id'], 'exist', 'skipOnError' => true, 'targetClass' => Extension::className(), 'targetAttribute' => ['extension_id' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'name' => Yii::p('cii', 'Name'),
            'value' => Yii::p('cii', 'Value'),
            'extension_id' => Yii::p('cii', 'Extension')
        ];
    }

    public function getExtension() {
        return $this->hasOne(Extension::className(), ['id' => 'extension_id']);
    }

    public function getField() {
        $config = [
            'value' => $this->value,
            'attribute' => 'value'
        ];

        if($this->type == 'in') {
            $config['values'] = $this->getValues();
        }
        
        return Yii::$app->cii->createFieldObject($this->type, $config);
    }


    public function validateValue($attribute, $params) {
        $field = $this->getField();
        if(!$field->validate()) {
            foreach($field->errors as $values) {
                foreach($values as $val) {
                    $this->addError('value', $val);
                }
            }
        }
    }

    
    public function getPreparedValue() {
        return $this->getField()->getPreparedValue($this);
    }

    public function getLabel() {
        $type = $this->getTypes();
        return isset($type['label']) ? $type['label'] : ucfirst($this->name);
    }
    
    public function getValues() {
        $type = $this->getTypes();
        return isset($type['values']) ? $type['values'] : [];
    }

    public function getType() {
        $type = $this->getTypes();
        return isset($type['type']) ? strtolower($type['type']) : 'text';
    }

    public function getTranslatedType() {
        return Yii::p('cii', ucfirst($this->type));
    }

    protected function getTypes() {
        if(!$this->_types) {
            $this->_types = $this->extension->getReflection()->getSettingTypes();
        }
        
        return isset($this->_types[$this->name]) ? $this->_types[$this->name] : null;
    }
}
