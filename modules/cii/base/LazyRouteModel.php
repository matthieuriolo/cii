<?php

namespace app\modules\cii\base;

use Yii;
use cii\behavior\ExtendableInterface;
use yii\base\InvalidConfigException;
use app\modules\cii\models\Route;


abstract class LazyRouteModel extends LazyModel implements ExtendableInterface {
    static public function canOutboxFrom($class) {
        return $class instanceof Route;
    }
    
    public function getRoute() {
        return $this->hasOne(Route::className(), [Route::primaryKey()[0] => 'route_id']);
    }

    static public function getOutboxAttribute($class) {
      return 'route_id';
    }
}
