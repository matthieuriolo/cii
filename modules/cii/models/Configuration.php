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
            
            [['name'], 'unique'],
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


    public function validateValue($attribute, $params) {
        $value = $this->$attribute;

        switch($this->type) {
            case 'email':
            case 'float':
            case 'boolean':
                $validator = Validator::createValidator($this->type, $this, ['value']);
                $validator->validateAttributes($this, ['value']);
                break;
            case 'integer':
                $validator = Validator::createValidator('integer', $this, ['value'], ['integerOnly' => true]);
                $validator->validateAttributes($this, ['value']);
                break;
            case 'in':
                $validator = Validator::createValidator('in', $this, ['value'], ['range' => array_keys($this->getValues())]);
                $validator->validateAttributes($this, ['value']);
                break;
            case 'password':
            case 'text':
            case 'color':
                break;
            case 'image':
                $path = Yii::getAlias('@webroot') . '/' . $value;
                if(!is_file($path) || (false === getimagesize($path))) {
                    $model->addError('value', 'No valid image path or not an image');
                }
                break;
            default:
                throw new InvalidConfigException();
        }
    }

    
    public function getPreparedValue() {
        switch($this->type) {
            case 'float':
                return floatval($this->value);
            case 'integer':
                return intval($this->value);
            case 'color':
            case 'in':
            case 'password':
            case 'text':
            case 'image':
            case 'email':
                return $this->value;
            case 'boolean':
                return $this->value == '0' ? false : true;
            default:
                throw new InvalidConfigException();
        }
    }

    

    public function render($view, $form) {
        $type = $this->type;
        switch($type) {
            case 'color':
            case 'boolean':
            case 'in':
            case 'password':
                break;
            default:
                $type = 'text';
        }

        return $view->render('_type_' . $type, [
            'form' => $form,
            'model' => $this
        ]);
    }

    public function getType() {
        $type = $this->getTypes();
        return isset($type['type']) ? strtolower($type['type']) : 'text';
    }

    public function getValues() {
        $type = $this->getTypes();
        return isset($type['values']) ? $type['values'] : [];
    }

    public function getLabel() {
        $type = $this->getTypes();
        return isset($type['label']) ? $type['label'] : ucfirst($this->name);
    }

    protected function getTypes() {
        if(!$this->_types) {
            $this->_types = $this->extension->getReflection()->getSettingTypes();
        }
        
        return isset($this->_types[$this->name]) ? $this->_types[$this->name] : null;
    }

    /*
    public function getSetting() {
        $module = Yii::$app->getModule($this->extension->name);
        $types = $module->getSettingTypes();
        $type = $types[$this->name];
        return Yii::createObject([
            'class' => 'cii\base\Configuration',
            'id' => $this->extension->name,

            'key' => $this->name,
            'label' => isset($type['label']) ? $type['label'] : null,
            
            'default' => isset($type['default']) ? $type['default'] : null,
            'type' => isset($type['type']) ? $type['type'] : 'text',
            'values' => isset($type['values']) ? $type['values'] : null,
        ]);
    }*/
}
