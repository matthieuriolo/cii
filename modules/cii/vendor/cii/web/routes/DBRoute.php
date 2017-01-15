<?php
namespace cii\web\routes;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;

use app\modules\cii\models\common\Route;
use app\modules\cii\models\common\CountAccess;


use cii\helpers\UTC;
use cii\helpers\Url;

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

    public function getCalledModelRoute() {
        return $this->getDBModel();
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

    public function increaseCounter() {
        $db = $this->getDBModel();
        $model = CountAccess::findOne([
            'created' => UTC::date(),
            'route_id' => $db->id
        ]);

        if(!$model) {
            $model = new CountAccess();
            $model->route_id = $this->getDBModel()->id;
            $model->created = UTC::date();
        }

        $bounceRate = false;
        if(!empty(Yii::$app->request->referrer)) {
            $info = parse_url(Yii::$app->request->referrer);
            if(!empty($info) && $info['host'] == $_SERVER['SERVER_NAME']) {
                $bounceRate = true;
            }
        }

        $isBot = false;
        if(!empty($_SERVER['HTTP_USER_AGENT'])) {
            if(preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
                $isBot = true;
            }
        }

        if($bounceRate) $model->bounceHits++;
        if($isBot) $model->botHits++;

        $model->hits++;
        $model->save();


        if($bounceRate) $db->bounceHits++;
        if($isBot) $db->botHits++;
        
        $db->hits++;
        $db->save();
    }
}
