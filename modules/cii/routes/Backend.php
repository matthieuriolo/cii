<?php 

namespace app\modules\cii\routes;

class Backend extends \cii\web\routes\ControllerRoute {
    public $baseRoute = 'cii/backend';
    public function getSubRoutes() {
    	return [
            //subroutes for packages different than cii
    		[
    			'class' => '\cii\web\routes\RegexRoute',
    			'match' => 'modules\/(\w+)',
    			'replace' => '$1/$*',
    		],

            //redirect to actions
            [
                'class' => '\cii\web\routes\RegexRoute',
                'match' => '^[^\/]+$',
                'replace' => 'cii/backend/$0',
            ],

            //redirect to controllers
            [
                'class' => '\cii\web\routes\RegexRoute',
                'match' => '.+',
                'replace' => 'cii/$0',
            ],
    	];
    }
}