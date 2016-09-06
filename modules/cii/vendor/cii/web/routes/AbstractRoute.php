<?php
namespace cii\web\routes;

use Yii;
use yii\base\Object;
use app\modules\cii\models\Route;
use yii\base\InvalidConfigException;

abstract class AbstractRoute extends Object implements RouteInterface {
    public $route;
    public $parentRoute;
    public $offset = 0;
    
    public function init() {
        if($this->route === null) {
            throw new InvalidConfigException('UrlRule::route must be set.');
        }
        
        $this->route = trim($this->route, '/');
        parent::init();
    }
    

    public function relativeRoute($controller, $route) {
    	$node = $this->findRoute($controller);
    	return '/' . $node->getBaseRoute() . '/' . $route;
    }

    public function getCalledModelRoute() {
        return $this->parentRoute ? $this->parentRoute->getCalledModelRoute() : null;
    }


    public function relativeAdminRoute($route) {
        return $this->relativeRoute('app\modules\cii\routes\Backend', $route);
    }

    public function lastRoute($route) {
        return $this->getBaseRoute() . '/' . $route;
    }

    public function findRoute($controller, $throwException = true) {
        $routeClass = null;
        $node = $this;

        while($node) {
            if($node instanceof $controller) {
                break;
            }

            $node = $node->parentRoute;
        }

        if(!$node) {
            if($throwException) {
                throw new \Exception('Could not resolve the relative route (' . $controller . ' )');
            }

            return null;
        }

        return $node;
    }

    public function getBaseRoute() {
        $ret = substr($this->route, 0, $this->offset);
        return trim($ret, '/');
    }
}
