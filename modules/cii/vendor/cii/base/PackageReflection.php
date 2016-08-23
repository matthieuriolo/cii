<?php


namespace cii\base;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;


use app\modules\cii\models\Package as Core_Module;
use app\modules\cii\models\Extension as Core_Extension;
use app\modules\cii\models\Classname as Classname;
use yii\db\Expression;

use yii\base\Object;

class PackageReflection extends BaseReflection {
    protected function versionCompare($module, $version) {
        $min = null;
        $max = null;
        if(is_array($version) && count($version) > 0) {
            $min = $version[0];
            if(count($version) > 1) {
                $max = $version[1];
            }
        }else if(is_string($version)){
            $min = $version;
        }

        $hasModule = Core_Module::find()
            ->joinWith('extension as ext')
            ->where([
                'ext.name' => $module,
            ])
            ->one();

        if(!$hasModule) {
            return Yii::t('app', 'The package {pkg} is missing', ['pkg' => $module]);
        }
        

        if($min) {
            $ref = new PackageReflection();
            if(!$ref->load($this->getInstallationPath() . '/' . $module)) {
                return false;
            }

            if(!version_compare($min, $ref->getVersion(), '>=')) {
                return Yii::t('app', 'The package {pkg} has only version {version} (demanded is {min})', [
                    'pkg' => $module,
                    'min' => $min,
                    'version' => $ref->getVersion()
                ]);
            }

            if($max) {
                if(!version_compare($max, $ref->getVersion(), '<=')) {
                    return Yii::t('app', 'The package {pkg} version is a greater than demanded  - {version} (demanded is {max})', [
                        'pkg' => $module,
                        'max' => $min,
                        'version' => $ref->getVersion()
                    ]);
                }
            }
        }

        return true;
    }

    

    protected function getInstallationPath() {
        return Yii::getAlias(Yii::$app->modulePath);
    }
    
    public function checkDependencies() {
        $ret = [];
        if(isset($this->data['dependencies'])) {
            foreach($this->data['dependencies'] as $module => $version) {
                if(($test = $this->versionCompare($module, $version)) !== true) {
                    $ret[] = $test;
                }
            }
        }

        return count($ret) == 0 ? true : $ret;
    }

    public function check() {
        if(($ret = parent::check()) === true) {
            return $this->checkDependencies();
        }

        return $ret;
    }
    
    protected function getExtensionClassName() {
        return  Core_Module::className();
    }

    protected function migrate($enabled = false) {
        $version = $this->getInstalledVersion();
        $class = [
            'class' => null,
            'file' => null
        ];

        if(!$version) {
            //no earlier version installed
            $class = [
                'class' => 'app\\modules\\' . $this->getName() . '\\migrations\\Index',
                'file' => $this->getInstallationPath() . '/' . $this->getName() . '/migrations/index.php',
            ];
        }else {
            //found a version, test if uptodate or needs migration
        }

        if($class['class'] && is_file($class['file'])) {
            require_once($class['file']);
            $c = $class['class'];
            $migrator = new $c();
            $migrator->up();
        }

        parent::migrate($enabled);
    }
}
