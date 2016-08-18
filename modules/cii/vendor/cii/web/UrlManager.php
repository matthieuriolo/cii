<?php
namespace cii\web;

use Yii;
use cii\web\routes\RouteInterface;
use yii\base\InvalidConfigException;
use yii\caching\Cache;

class UrlManager extends \yii\web\UrlManager {
/*	public $enablePrettyUrl = false;
    public $enableStrictParsing = false;
    public $suffix;
    public $showScriptName = true;
    public $routeParam = 'r';
    public $cache = 'cache';
    
    public $ruleConfig = ['class' => 'yii\web\UrlRule'];

    protected $cacheKey = __CLASS__;

    private $_baseUrl;
    private $_scriptUrl;
    private $_hostInfo;
    private $_ruleCache;
*/
    
    public $ruleConfig = ['class' => 'cii\web\routes\DBRoute'];

    public function getCalledRoute($request = null) {
        if(is_null($request)) {
            $request = Yii::$app->getRequest();
        }

        if($this->enablePrettyUrl) {
            $route = $request->getPathInfo();
        }else {
            $route = $request->getQueryParam($this->routeParam, '');
        }

        return $route;
    }

    public function parseRequest($request) {
		if($this->enablePrettyUrl) {
			$route = $request->getPathInfo();
		}else {
			$route = $request->getQueryParam($this->routeParam, '');
	        if(!is_string($route)) {
	            $route = '';
	        }
	    }


	    if(empty($route)) {
	    	return ['cii/site/index', [], null];
	    }

	    $route = Yii::createObject(array_merge($this->ruleConfig, [
	    	'route' => $route,
	    	'offset' => 0
	    ]));

	    if(!$route instanceof RouteInterface) {
	    	throw new InvalidConfigException('Route class must implement RouteInterface.');
	    }

	    if(($result = $route->parseRoute($this, $request)) !== false) {
	    	return $result;
	    }


		return false;
    }


    public function createUrl($params) {
        $params = (array) $params;
        $anchor = isset($params['#']) ? '#' . $params['#'] : '';
        unset($params['#'], $params[$this->routeParam]);

        $route = trim($params[0], '/');
        unset($params[0]);

        $baseUrl = $this->showScriptName || !$this->enablePrettyUrl ? $this->getScriptUrl() : $this->getBaseUrl();

        $url = "$baseUrl?{$this->routeParam}=" . urlencode($route);
        if(!empty($params)) {
            
        
            if(isset($params[1]) && is_array($params[1]) && ($query = http_build_query($params[1])) !== '') {
        	   $url .= '&' . $query;
            }else if(($query = http_build_query($params)) !== '') {
               $url .= '&' . $query;
            }
        }

        return $url . $anchor;
    
    }
}