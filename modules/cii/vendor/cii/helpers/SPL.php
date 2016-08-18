<?php
namespace cii\helpers;
use Yii;

class SPL {
	public static function hasInterface($class, $interface) {
        return in_array($interface, class_implements($class));
    }

    /*
    public static function hasBehavior($class, $interface) {
        
    }*/
}
