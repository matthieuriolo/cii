<?php
namespace cii\web\routes;

use Yii;

interface RouteInterface {
	public function parseRoute($manager, $request);
}