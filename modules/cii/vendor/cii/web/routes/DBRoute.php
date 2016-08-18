<?php
namespace cii\web\routes;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;

use app\modules\cii\models\Route;

class DBRoute extends AbstractRoute {
    protected function getRouteName() {
    	$route = $this->route;
        if(($pos = strpos($route, '/', $this->offset)) !== false) {
        	$name = substr($route, $this->offset, $pos);
		}else if($this->offset < strlen($route)) {
			$name = substr($route, $this->offset);
		}

		return $name;
    }

    protected function getDBModel() {
        $name = $this->getRouteName();
    	$parent_id = null;
    	if($this->parentRoute) {
    		$parent_id = $this->parentRoute->parent_id;
    	}

		return Route::find()
            ->where([
            	'slug' => $name,
            	'parent_id' => $parent_id
            ])
            ->one();
    }

    public function parseRoute($manager, $request) {
    	$model = $this->getDBModel();
            
        if(!$model) {
        	return false;
        }

        if($this->offset == strlen($this->route)) {
			return false;
		}

        $route = \Yii::createObject($model->outbox()->getRouteConfig() + [
            'offset' => strlen($this->getRouteName()) + 1,
            'parentRoute' => $this,
            'route' => $this->route
        ]);

        return $route->parseRoute($manager, $request);
    }
}
