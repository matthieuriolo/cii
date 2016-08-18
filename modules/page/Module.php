<?php 

namespace app\modules\page;

use Yii;

class Module extends \cii\backend\Package {
    public function getBackendItems() {
    	return [
    		'name' => 'Page',
    		'url' => [Yii::$app->seo->relativeAdminRoute('package'), ['name' => $this->id]],
    		'icon' => 'glyphicon glyphicon-font',
    	];
    }
}