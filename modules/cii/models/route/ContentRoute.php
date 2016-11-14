<?php

namespace app\modules\cii\models\route;

use Yii;
use cii\behavior\ExtendableInterface;

use app\modules\cii\base\LazyRouteModel;
use app\modules\cii\models\common\Route;
use app\modules\cii\models\common\Content;

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
            'route_id' => Yii::p('cii', 'Route'),
            'content_id' => Yii::p('cii', 'Content'),

            'type' => Yii::p('cii', 'Meta type'),
            'robots' => Yii::p('cii', 'Meta Robots'),
            'keys' => Yii::p('cii', 'Meta keys'),
            'description' => Yii::p('cii', 'Meta description'),
            'image' => Yii::p('cii', 'Meta image'),
        ];
    }

    public function getContent() {
        return $this->hasOne(Content::className(), [Content::primaryKey()[0] => 'content_id']);
    }

    public function getTypesForDropdown() {
        return [
            null => Yii::p('cii', 'No selection'),
            'website' => Yii::p('cii', 'Website'),
            'article' => Yii::p('cii', 'Article'),
        ];
    }

    public function getRobotTypesForDropdown() {
        return [
            null => Yii::p('cii', 'No selection'),
            'index,follow' => Yii::p('cii', 'Indexing and follow'),
            'noindex,follow' => Yii::p('cii', 'No indexing but follow'),
            'index,nofollow' => Yii::p('cii', 'Indexing but not follow'),
            'noindex,nofollow' => Yii::p('cii', 'Neither indexing or following'),
        ];
    }

    static public function getTypename() {
      return 'Cii:Content';
    }


    public function getRouteConfig() {
        return ['class' => 'app\modules\cii\routes\content'];
    }

    public function forwardToController($controller) {
        return Yii::$app->runAction('cii/site/content', Yii::$app->request->queryParams);
    }

    static public function getRouteToController() {
        return 'cii/content';
    }
}
