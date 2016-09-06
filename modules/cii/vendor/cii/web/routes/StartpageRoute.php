<?php
namespace cii\web\routes;

use Yii;
use yii\base\Object;
use app\modules\cii\models\Route;
use yii\base\InvalidConfigException;

class StartpageRoute extends AbstractRoute {
    public $route = '';
    public $parentRoute;
    public $loadedModel;
    public $route_id;

    public function getModel() {
        return $this->loadedModel;
    }

    public function parseRoute($manager, $request) { return null; }
}
