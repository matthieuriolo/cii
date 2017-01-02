<?php
namespace cii\helpers;

class UTC {
	public static function datetime() {
        return gmdate('Y-m-d H:i:s');
    }

    public static function date() {
        return gmdate('Y-m-d');
    }

    public static function strtotime($str) {
		$date = new \DateTime($str, new \DateTimeZone('UTC'));
		return $date->getTimestamp();
	}
}
