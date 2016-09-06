<?php

namespace app\modules\cii\models;

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
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%Cii_Abstract_Content}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'enabled', 'type'], 'required'],
            [['enabled'], 'boolean'],
            [['name'], 'string', 'max' => 255],

            [['type'], 'in', 'range' => Yii::$app->cii->layout->getContentTypeValues()]
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
