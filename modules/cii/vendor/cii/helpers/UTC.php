<?php
namespace cii\helpers;

class UTC {
	public static function datetime() {
        return gmdate('Y-m-d H:i:s');
    }

    public static function date() {
        return gmdate('Y-m-d');
    }
}
