<?php

namespace app\modules\cii\models;

use Yii;
use cii\behavior\ExtendableInterface;

use app\modules\cii\base\LazyRouteModel;

/**
 * This is the model class for table "Core_ContentRoute".
 *
 * @property integer $id
 * @property integer $route_id
 *
 * @property CoreRoute $route
 */
class ContentRoute extends LazyRouteModel {
    public $allowChildren = true;
    public static $lazyControllerRoute = 'cii/content';

    public static function tableName() {
        return '{{%Cii_ContentRoute}}';
    }

    public function rules() {
        return [
            [['route_id', 'content_id'], 'required'],
            [['route_id', 'content_id'], 'integer'],
            [['route_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['route_id' => 'id']],
            [['content_id'], 'exist', 'skipOnError' => true, 'targetClass' => Content::className(), 'targetAttribute' => ['content_id' => 'id']],
            

            [['type'], 'string', 'max' => 24],
            [['robots'], 'string', 'max' => 16],
            [['keys', 'description', 'image'], 'string', 'max' => 255],

            [['type'], 'in', 'range' => array_keys($this->getTypesForDropdown())],
            [['robots'], 'in', 'range' => array_keys($this->getRobotTypesForDropdown())]
        ];


    }

    public function attributeLabels() {
        return [
            'route_id' => Yii::t('app', 'Route'),
            'content_id' => Yii::t('app', 'Content'),

            'type' => 'Meta type',
            'robots' => 'Meta Robots',
            'keys' => 'Meta keys',
            'description' => 'Meta description',
            'image' => 'Meta image',
        ];
    }

    public function getContent() {
        return $this->hasOne(Content::className(), [Content::primaryKey()[0] => 'content_id']);
    }

    public function getTypesForDropdown() {
        return [
            null => Yii::t('app', 'No selection'),
            'website' => Yii::t('app', 'Website'),
            'article' => Yii::t('app', 'Article'),
        ];
    }

    public function getRobotTypesForDropdown() {
        return [
            null => Yii::t('app', 'No selection'),
            'index,follow' => Yii::t('app', 'Indexing and follow'),
            'noindex,follow' => Yii::t('app', 'No indexing but follow'),
            'index,nofollow' => Yii::t('app', 'Indexing but not follow'),
            'noindex,nofollow' => Yii::t('app', 'Neither indexing or following'),
        ];
    }

    static public function getTypename() {
      return 'Cii:Content';
    }


    public function getRouteConfig() {
        return ['class' => 'app\modules\cii\routes\content'];
    }

    static public function getRouteToController() {
        return 'cii/content';
    }
}
