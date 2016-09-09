<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace cii\base;

use Yii;
use yii\base\Module;
use app\modules\cii\models\Package as Core_Module;

class Package extends Module {
    protected $_reflection;
    protected $_dbentry;

    public function getDisplayName() {
        return $this->getReflection()->getDisplayName();
    }

    public function getVersion() {
        return $this->getReflection()->getVersion();
    }

    protected function getDBEntry() {
        if(!$this->_dbentry) {
            $this->_dbentry = Core_Module::find()
                ->joinWith(['extension as ext'])
                ->where(['ext.name' => $this->id])
                ->one();
        }

        return $this->_dbentry;
    }

    public function getIdentifier() {
        return $this->getDBEntry()->id;
    }
    
    public function getEnabled() {
        return $this->getDBEntry()->enabled;
    }

    public function getPermissionTypes() {
        return [];
    }

    public function getReflection() {
        if(!$this->_reflection) {
            return $this->_reflection = Yii::$app->cii->package->getReflection($this->id);
        }

        return $this->_reflection;
    }

    public function getSettingTypes() {
        return $this->getReflection()->getSettingTypes();
    }

    public function getFieldTypes() {
        return $this->getReflection()->getFieldTypes();
    }
}
