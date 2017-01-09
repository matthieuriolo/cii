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
    const MANAGE_PACKAGE = 8;
    const MANAGE_BROWSER = 9;

    const MANAGE_ADMIN = 10;
    const MANAGE_MANDATE = 11;
    
    static public function getPermissions() {
    	return [
    		static::MANAGE_ADMIN => Yii::p('cii', 'Admin'),

            static::MANAGE_ROUTE => Yii::p('cii', 'Manage routes'),
    		static::MANAGE_CONTENT => Yii::p('cii', 'Manage contents'),
    		static::MANAGE_USER => Yii::p('cii', 'Manage users'),
    		static::MANAGE_GROUP => Yii::p('cii', 'Manage groups'),
    		static::MANAGE_SETTING => Yii::p('cii', 'Manage settings'),
    		static::MANAGE_LOG => Yii::p('cii', 'Manage log'),
    		static::MANAGE_EXTENSION => Yii::p('cii', 'Manage extensions'),
    		static::MANAGE_LAYOUT => Yii::p('cii', 'Manage layouts'),
    		static::MANAGE_LANGUAGE => Yii::p('cii', 'Manage languages'),
            static::MANAGE_PACKAGE => Yii::p('cii', 'Manage packages'),
            static::MANAGE_BROWSER => Yii::p('cii', 'Manage files'),
    	];
    }
}