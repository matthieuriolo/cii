<?php 

namespace app\modules\cii\routes;

class Backend extends \cii\web\routes\ControllerRoute {
    public $baseRoute = 'cii/backend';
    public function getSubRoutes() {
    	return [
    		[
    			'class' => '\cii\web\routes\RegexRoute',
    			'match' => 'modules\/(\w+)',
    			'replace' => '$1/$*',
    		],

    		[
    			'class' => '\cii\web\routes\RegexRoute',
    			'match' => 'mandate\/(\w+)',
    			'replace' => '$1/$*',
    		],

    		[
    			'class' => '\cii\web\routes\RegexRoute',
    			'match' => 'doc\/(\w+)',
    			'replace' => '$1/$*',
    		],
    	];
    }
}