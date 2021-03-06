<?php

namespace app\modules\cii\models\common;

use Yii;

/**
 * This is the model class for table "Core_Content".
 *
 * @property integer $id
 * @property string $name
 * @property integer $ordering
 * @property integer $enabled
 *
 * @property CoreAuthContent[] $coreAuthContents
 * @property CoreContentVisibilities[] $coreContentVisibilities
 */
class Content extends \yii\db\ActiveRecord {
    public $type;

    public function init() {
        if(is_null($this->columns_count)) {
            $this->columns_count = 2;
        }
        
        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%Cii_Abstract_Content}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'enabled', 'type'], 'required'],
            [['enabled', 'show_name'], 'boolean'],
            [['name'], 'string', 'max' => 255],
            [['columns_count'], 'integer', 'min' => 1],
            [['type'], 'in', 'range' => Yii::$app->cii->layout->getContentTypeValues()],
            [['classname_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classname::className(), 'targetAttribute' => ['classname_id' => 'id']],
        ];
    }

    public function afterFind() {
        $this->type = $this->classname->path;
        return parent::afterFind();
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => Yii::p('cii', 'Name'),
            'enabled' => Yii::p('cii', 'Enabled'),
            'classname' => Yii::p('cii', 'Type'),
            'created' => Yii::p('cii', 'Created'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthContents() {
        return $this->hasMany(AuthContent::className(), ['content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentVisibilities() {
        return $this->hasMany(ContentVisibilities::className(), ['content_id' => 'id']);
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
