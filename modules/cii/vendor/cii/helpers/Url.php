<?php
namespace cii\helpers;
use Yii;

class Url extends \yii\helpers\Url {
	public static function toRoute($route, $scheme = false)
    {
        $route = (array) $route;
        $route[0] = static::normalizeRoute($route[0]);
        
        if ($scheme) {
            return static::getUrlManager()->createAbsoluteUrl($route, is_string($scheme) ? $scheme : null);
        } else {
            return static::getUrlManager()->createUrl($route);
        }
    }


    protected static function normalizeRoute($route) {
        if(is_null(Yii::$app->seo)) {
            return parent::normalizeRoute($route);
        }

        //$route = Yii::getAlias((string) $route);
        if (strncmp($route, '/', 1) === 0) {
            // absolute route
            return ltrim($route, '/');
        }

        // relative route
        if (Yii::$app->controller === null) {
            throw new InvalidParamException("Unable to resolve the relative route: $route. No active controller is available.");
        }


        return Yii::$app->seo->getBaseRoute() . '/' . $route;


/*
        if (strpos($route, '/') === false) {
            // empty or an action ID
            return $route === '' ? Yii::$app->controller->getRoute() : Yii::$app->controller->getUniqueId() . '/' . $route;
        } else {
            // relative to module
            return ltrim(Yii::$app->controller->module->getUniqueId() . '/' . $route, '/');
        }
        */
    }
}
