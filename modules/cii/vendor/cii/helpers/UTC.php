<?php
namespace cii\helpers;

use Yii;
use yii\helpers\ArrayHelper;

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

    public static function timezones() {
        return ArrayHelper::map(timezone_identifiers_list(), 'id', function($val) {
            return Yii::p('cii', $val);
        });
    }
}
