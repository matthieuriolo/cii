<?php

namespace app\modules\cii\models;

use Yii;
use yii\db\ActiveRecord;


class Route extends ActiveRecord {
    public $type;
	public $throwErrorUnboxed = true;
    
    public static function tableName() {
        return '{{%Cii_Route}}';
    }

    public function rules() {
        return [
            [['slug', 'enabled', 'type'], 'required'],
            [['slug'], 'string', 'max' => 45],
            [['parent_id', 'language_id'], 'integer'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => self::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['slug'], 'valideSlug'],
            [['enabled'], 'boolean'],


            [['type'], 'in', 'range' => Yii::$app->cii->route->getTypeValues()]
        ];
    }
    
    public function valideSlug($attribute, $params) {
        $val = $this->$attribute;
        if(preg_match('/^([A-Z]|[a-z]|\d|\.|-|_)+$/', $val)) {
            return true;
        }

        $this->addError($attribute, $this->getAttributeLabel($attribute) . ' can only consist of A-Z, 0-9, -, . or _');
        return false;
    }

    /* belongs to an own behaviour */
    public function getParentChain() {
        $tks = [$this];
        $node = $this;
        while($node = $node->parent) {
            $tks[] = $node;
        }

        return array_reverse($tks);
    }

    /* belongs to an own behaviour */
    public function getParent() {
        return $this->hasOne(self::className(), ['id' => 'parent_id'])->inverseOf('children');
    }

    public function getFullSlug() {
        $tks = $this->getParentChain();
        return array_map(function($route) {
            return $route->slug;
        }, $tks);
    }

    public function getBreadcrumbs() {
        $arrs = $this->getFullSlug();
        return implode('/', $arrs);
    }

    public function getChildren() {
        return $this->hasMany(self::className(), ['parent_id' => 'id'])->inverseOf('parent');
    }

    public function hasChildren() {
        return self::findOne(['parent_id' => $this->id]) ? true : false;
    }

    public function getLanguage() {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'enabled' => 'Enabled',
            'classname' => 'Type',
            'language_id' => 'Language',
            'parent_id' => 'Parent',
            'breadcrumb' => 'Breadcrumb',
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => 'cii\behavior\ExtendableBehavior',
                'throwErrorUnboxed' => true,
            ],

            [
                'class' => 'cii\behavior\DatetimeBehavior',
                'create' => 'created'
            ]
        ];
    }
}
