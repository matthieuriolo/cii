<?php 

namespace app\modules\cii\routes;

class BackendModules extends \cii\web\routes\RegexRoute {
    public $match = 'modules\/(\w+)';
    public $replace = '$1/$*';
}