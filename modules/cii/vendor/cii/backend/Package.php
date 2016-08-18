<?php


namespace cii\backend;

use Yii;

class Package extends \cii\base\Package {
    public function getBackendItems() {
    	return [
    		'name' => $this->id,
    		'url' => ['admin/package', ['name' => $this->id]],
    		'icon' => '',
    		'children' => []
    	];
    }

    public function getRouteTypes() {
    	return [];
    }

    public function getContentTypes() {
        return [];
    }
}
