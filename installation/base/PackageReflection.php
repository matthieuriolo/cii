<?php

namespace app\base;

use cii\base\PackageReflection as CPR;

class PackageReflection extends CPR {
	protected function getInstallationPath() {
        return realpath(__DIR__ . '/../../modules');
    }


    protected function getInstalledVersion() {
    	try {
    		//catch errors silently since the cii tables might not exist
    		return $parent::getInstalledVersion();
    	}catch(\Exception $e) {}
    	return null;
    }
}