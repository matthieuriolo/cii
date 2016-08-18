<?php
namespace cii\web;

use Yii;
use yii\base\UserException;

class SecurityException extends UserException {
	public function getName() {
        return 'You are obviously trying to hack something. Please dont do that';
    }
}
