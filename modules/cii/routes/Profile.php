<?php 

namespace app\modules\cii\routes;

class Profile extends \cii\web\routes\ControllerRoute {
    public $baseRoute = 'cii/profile';

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