<?php
namespace cii\web\routes;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;

use app\modules\cii\models\Route;

class ControllerRoute extends DBRoute {
    public $baseRoute;
    
    public function getModel() {
        return $this->parentRoute->getDBModel()->outbox();
    }
    
    public function init() {
        if($this->baseRoute === null) {
            throw new InvalidConfigException('UrlRule::baseRoute must be set.');
        }
        
        parent::init();
    }

    public function getSubRoutes() {
        return [];
    }

    protected function matchRoute($sub) {
        $matches = [];
        foreach($this->getSubRoutes() as $class) {

            $pattern = '/' . $name . '/';
            if(preg_match($pattern, $sub)) {
                $matches[] = preg_replace($pattern, $subroute, $sub);
                break;
            }
        }

        if(count($matches)) {
            $match = reset($matches);
            return $match;
        }

        return false;
    }

    public function parseRoute($manager, $request) {
        $suffix = '';
        if(($sub = substr($this->route, $this->offset)) && !empty($sub)) {
            foreach($this->getSubRoutes() as $class) {

                if(is_string($class)) {
                    $class = ['class' => $class];
                }

                $subroute = Yii::createObject($class + [
                    'parentRoute' => $this,
                    'route' => $this->route,
                    'offset' => $this->offset
                ]);

                if($route = $subroute->parseRoute($manager, $request)) {
                   return $route; 
                }
            }

            $suffix = '/' . $sub;
        }
        
        return [$this->baseRoute . $suffix, [], $this];
    }


    public function increaseCounter() {
        $this->parentRoute->increaseCounter();
    }
}
