<?php

namespace app\modules\cii\base;

use Yii;
use cii\behavior\ExtendableInterface;
use app\modules\cii\models\Content;

abstract class LazyContentModel extends LazyModel implements ExtendableInterface {
    public $canBeShadowed = false;

    static public function canOutboxFrom($class) {
        return $class instanceof Content;
    }
    
    public function getContent() {
        return $this->hasOne(Content::className(), [Content::primaryKey()[0] => 'content_id']);
    }

    static public function getOutboxAttribute($class) {
      return 'content_id';
    }

    public function getShadowInformation() {
        throw new InvalidConfigException();
    }
}
