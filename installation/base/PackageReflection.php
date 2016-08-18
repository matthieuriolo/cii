<?php

namespace app\base;

use cii\base\PackageReflection as CPR;

class PackageReflection extends CPR {
	protected function getModulePath() {
        return realpath(__DIR__ . '/../../modules');
    }
}