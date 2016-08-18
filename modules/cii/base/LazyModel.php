<?php

namespace app\modules\cii\base;

use Yii;
use yii\base\InvalidConfigException;

class LazyModel extends \yii\db\ActiveRecord implements LazyModelInterface {
    public static $lazyControllerRoute = null;

    static public function hasLazyCRUD() {
        return !empty(static::$lazyControllerRoute);
    }    

    static public function getLazyCRUD() {
        if(empty(static::$lazyControllerRoute)) {
            throw new InvalidConfigException();
        }

        return [
            'controller' => Yii::$app->createController(static::$lazyControllerRoute)[0],
            'label' => 'getLazyLabel',
            'view' => 'getLazyView',
            'create' => 'getLazyCreate',
            'update' => 'getLazyUpdate',  
        ];
    }
}
