<?php


namespace cii\base;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;


use app\modules\cii\models\extension\Extension as Core_Extension;
use app\modules\cii\models\extension\Classname;
use yii\db\Expression;

use yii\base\Object;

abstract class BaseReflection extends Object {
    const INFORMATION_FILE = 'index.php';
    const SETTING_FILE = 'settings.php';

    protected $data;
    protected $basePath;

    protected $tmpModulePath = null;

    public function load($dir) {
        if(($dir = realpath($dir)) && is_dir($dir)) {
            $path = $dir . '/' . self::INFORMATION_FILE;
            if(is_file($path)) {
                $this->data = require($path);
                if(is_array($this->data) && isset($this->data['name'], $this->data['version'], $this->data['type'])) {
                    $this->basePath = $dir;
                    return true;
                }
            }
        }

        return false;
    }

    public function loadByName($name) {
        return $this->load($this->getInstallationPath() . '/' . $name);
    }

    public function getType() {
        return $this->data['type'];
    }

    public function getName() {
        return $this->data['name'];
    }

    public function getDisplayName() {
        if(isset($this->data['displayName'])) {
            return $this->data['displayName'];
        }

        return ucfirst($this->getName());
    }

    public function getVersion() {
        return $this->data['version'];
    }

    public function getDescription() {
        return $this->data['description'];
    }

    public function getCreated() {
        return $this->getInfo('created');
    }


    public function getAuthorName() {
        return $this->getAuthorInfo('name');
    }

    public function getAuthorMail() {
        return $this->getAuthorInfo('email');
    }

    public function getAuthorSite() {
        return $this->getAuthorInfo('website');
    }

    protected function getAuthorInfo($name) {
        $data = $this->getInfo('author');
        return $data && isset($data[$name]) ? $data[$name] : null;
    }

    protected function getInfo($name) {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    public function isInstalled() {
        return $this->getInstalledVersion() ? true : false;
    }

    public function isEnabled() {
        if($model = $this->getInstalledVersion()) {
            return $model->enabled;
        }

        return false;
    }

    protected function inApplication() {
        $base = $this->getModulePath();
        return substr($this->basePath, 0, strlen($base)) === $base;
    }

    

    protected function preMove() {
        if(!$this->inApplication()) {
            //moves the selected package into the modules folder
            $targetDir = $this->getModulePath();
            $oldModulePath = $targetDir . '/' . $this->getName();
            
            //create backup of old module
            if(is_dir($oldModulePath)) {
                $this->tmpModulePath = $targetDir . '/_backup_'. $this->getName() . '_' . time();
                FileHelper::copyDirectory($oldModulePath, $this->tmpModulePath);
            }

            //copy new files
            FileHelper::copyDirectory($this->basePath, $oldModulePath);
        }
    }

    protected function postMove() {
        if(!is_null($this->tmpModulePath)) {
            FileHelper::removeDirectory($this->tmpModulePath);
            $this->tmpModulePath = null;
        }
    }

    public function check() {
        //test if there is already and existing extension
        //and if so test if the version is newer than our
        $self = new static();
        $path = $this->getInstallationPath() . '/' . $this->getName();
        if(
            $path != $this->basePath
            &&
            $self->load($path)
        ) {
            if(!version_compare($this->getVersion(), $self->getVersion(),'>')) {
                return [Yii::t('The extension is already installed with version {version}', [
                    'version' => $self->getVersion()
                ])];
            }
        }

        return $this->checkDependencies();
    }
    
    public function install($enabled = false) {
        if(($ret = $this->check()) === true) {
            $this->preMove();
            $this->migrate($enabled);
            $this->postMove();
            return true;
        }

        return $ret;
    }

    public function deinstall() {
        $transaction = Yii::$app->db->connection->beginTransaction();
        try {
            $this->getInstalledVersion()->remove();
        }catch(\Exception $e) {
            $transaction->rollback();
            throw $e;
        }

        $transaction->commit();
        FileHelper::removeDirectory($this->basePath);
    }
    

    abstract protected function getInstallationPath();
    abstract protected function getExtensionClassName();

    public function getInstalledVersion() {
        static $row = null;
        if(!$row) {
            $row = Core_Extension::find()
                ->where([
                    'name' => $this->getName(),
                    'classname.path' => $this->getExtensionClassName(),
                ])
                ->joinWith(['classname as classname'])
                ->one();
        }

        return $row;
    }
    
    protected function migrate($enabled = false) {
        $version = $this->getInstalledVersion();
        if(!$version) {
            $this->saveInstalledVersion($enabled);
        }
    }


    protected function saveInstalledVersion($enabled) {
        $model = $this->getExtensionClassName();

        $ext = new Core_Extension();
        $ext->name = $this->getName();
        $ext->enabled = $enabled;
        $ext->classname_id = Classname::registerModel($model::className());
        $ext->save();
        
        $model = new $model();
        $model->extension_id = $ext->id;
        $model->save();
    }


    public function getSettingTypes() {
        $path = $this->basePath . '/' . static::SETTING_FILE;
        
        if(is_file($path)) {
            return include($path);
        }

        return [];
    }

    public function getFieldTypes() {
        return isset($this->data['fieldTypes']) && is_array($this->data['fieldTypes']) ? $this->data['fieldTypes'] : [];
    }


    protected function versionCompare($packageName, $version) {
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
                'ext.name' => $packageName,
            ])
            ->one();

        if(!$hasModule) {
            return Yii::t('app', 'The package {pkg} is missing', ['pkg' => $packageName]);
        }
        

        if($min) {
            $ref = new PackageReflection();
            if(!$ref->load($this->getInstallationPath() . '/' . $packageName)) {
                return false;
            }

            if(!version_compare($min, $ref->getVersion(), '>=')) {
                return Yii::t('app', 'The package {pkg} has only version {version} (demanded is {min})', [
                    'pkg' => $packageName,
                    'min' => $min,
                    'version' => $ref->getVersion()
                ]);
            }

            if($max) {
                if(!version_compare($max, $ref->getVersion(), '<=')) {
                    return Yii::t('app', 'The package {pkg} version is a greater than demanded  - {version} (demanded is {max})', [
                        'pkg' => $packageName,
                        'max' => $min,
                        'version' => $ref->getVersion()
                    ]);
                }
            }
        }

        return true;
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
}
