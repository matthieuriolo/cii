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

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%Cii_ContentRoute}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['route_id', 'content_id'], 'required'],
            [['route_id', 'content_id'], 'integer'],
            [['route_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['route_id' => 'id']],
            [['content_id'], 'exist', 'skipOnError' => true, 'targetClass' => Content::className(), 'targetAttribute' => ['content_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'route_id' => 'Route',
            'content_id' => 'Content',
        ];
    }

    public function getContent() {
        return $this->hasOne(Content::className(), [Content::primaryKey()[0] => 'content_id']);
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
