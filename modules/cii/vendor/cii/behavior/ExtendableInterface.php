<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace cii\behavior;

use Yii;

interface ExtendableInterface {
    static public function canOutboxFrom($class);
    static public function getOutboxAttribute($class);
    static public function getTypename();
}
