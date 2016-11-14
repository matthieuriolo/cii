<?php 

namespace app\modules\cii\routes;

class Backend extends \cii\web\routes\ControllerRoute {
    public $baseRoute = 'cii/backend';
    public function getSubRoutes() {
    	return [
    		'app\modules\cii\routes\BackendModules',
    	];
    }
}