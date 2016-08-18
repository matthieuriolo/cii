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
    const INFORMATION_FILE = 'index.php';
    protected $info = null;

    public function getName() {
        return $this->getStaticInformations()['name'];
    }

    public function getDisplayName() {
        return ucfirst($this->name);
    }

    public function getVersion() {
        return $this->getStaticInformations()['version'];
    }

    protected function getStaticInformations() {
        return require($this->basePath . '/' . self::INFORMATION_FILE);
    }

    protected function getDBEntry() {
        static $row = null;
        if(!$row) {
            try {
                $row = Core_Module::find()
                    ->joinWith(['extension as ext'])
                    ->where(['ext.name' => $this->getName()])
                    ->one();
            }catch(\Exception $e) {}
        }

        return $row;
    }

    public function getIdentifier() {
        return $this->getDBEntry()->id;
    }
    
    public function getEnabled() {
        if(!$this->getInstalled()) {
            return false;
        }
        
        return $this->getDBEntry()->enabled;
    }

    public function getInstalled() {
        $val = $this->getDBEntry();
        return !empty($val);
    }


    public function getPermissionTypes() {
        return [];
    }

    public function getSettingTypes() {
        return [];
    }
}
