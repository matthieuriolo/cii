<?php

namespace app\modules\cii\base;

interface ContentInterface {
	public function forwardToController($controller);
	public function getShadowInformation();
}
