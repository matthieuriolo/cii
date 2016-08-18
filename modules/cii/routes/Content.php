<?php 

namespace app\modules\cii\routes;

class Content extends \cii\web\routes\ControllerRoute {
    public $baseRoute = 'cii/site/content';
/*
    public function getModel() {
    	return $this->parentRoute->getDBModel()->outbox();
    }
*/
    public function getSubRoutes() {
    	return [
    		[
		    	'class' => 'cii\web\routes\RegexRoute',
		    	'match' => 'captcha',
		    	'replace' => 'cii/site/captcha',
	    	]
	    ];
    }
}