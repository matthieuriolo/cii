<?php


namespace cii\base;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;


use app\modules\cii\models\Extension as Core_Extension;
use app\modules\cii\models\Classname as Classname;
use yii\db\Expression;

use yii\base\Object;

abstract class BaseReflection extends Object {
    const INFORMATION_FILE = 'index.php';
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

        return true;
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
}
