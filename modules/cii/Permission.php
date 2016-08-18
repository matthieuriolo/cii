<?php 

namespace app\modules\cii;
use Yii;


class Permission {
    const MANAGE_ROUTE = 0;
    const MANAGE_CONTENT = 1;
    const MANAGE_USER = 2;
    const MANAGE_GROUP = 3;
    const MANAGE_SETTING = 4;
    const MANAGE_LOG = 5;

    const MANAGE_EXTENSION = 6;
    const MANAGE_LAYOUT = 7;
    const MANAGE_LANGUAGE = 8;

    static public function getPermissions() {
    	return [
    		static::MANAGE_ROUTE => Yii::t('app', 'Manage routes'),
    		static::MANAGE_CONTENT => Yii::t('app', 'Manage contents'),
    		static::MANAGE_USER => Yii::t('app', 'Manage users'),
    		static::MANAGE_GROUP => Yii::t('app', 'Manage groups'),
    		static::MANAGE_SETTING => Yii::t('app', 'Manage settings'),
    		static::MANAGE_LOG => Yii::t('app', 'Manage log'),
    		static::MANAGE_EXTENSION => Yii::t('app', 'Manage extensions'),
    		static::MANAGE_LAYOUT => Yii::t('app', 'Manage layouts'),
    		static::MANAGE_LANGUAGE => Yii::t('app', 'Manage languages'),
    	];
    }
}