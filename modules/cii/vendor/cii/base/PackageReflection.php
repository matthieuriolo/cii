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

class PackageReflection extends Object {
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

    protected function getModulePath() {
        return Yii::$app->modulePath;
    }

    protected function inApplication() {
        $base = $this->getModulePath();
        return substr($this->basePath, 0, strlen($base)) === $base;
    }

    protected function preMove() {
        if(!$this->inApplication()) {
            //moves the selected package into the modules folder
            $targetDir = Yii::$app->modulePath;
            $oldModulePath = $targetDir . '/' . $this->getName();
            
            //create backup of old module
            if(is_dir($oldModulePath)) {
                $this->tmpModulePath = $targetDir . '/_backup_'. $this->getName() . '_' . time();
                FileHelper::copyDirectory($oldModulePath, $this->tmpModulePath);
            }

            FileHelper::copyDirectory($this->basePath, $oldModulePath);
        }
    }

    protected function postMove() {
        if(!is_null($this->tmpModulePath)) {
            FileHelper::removeDirectory($this->tmpModulePath);
        }
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
            if(!$ref->load($this->getModulePath() . '/' . $module)) {
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

    public function install($enabled = false) {
        if(($ret = $this->checkDependencies()) !== true) {
            return $ret;
        }
        
        $this->preMove();
        $this->migrate($enabled);
        $this->postMove();
        return true;
    }
    
    public function getInstalledVersion() {
        static $row = null;
        if(!$row) {
            try {
                $row = Core_Extension::find()
                    ->where([
                        'name' => $this->getName(),
                        'classname.path' => Core_Module::className(),
                    ])
                    ->joinWith(['classname as classname'])
                    ->one();
            }catch(\Exception $e) {}
        }

        return $row;
    }


    protected function getClassname($model) {
        if(is_object($model)) {
            $model = $model->className();
        }

        $ret = Classname::find()->where(['path' => $model])->one();
        if(!$ret) {
            $ret = new Classname();
            $ret->path = $model;
            $ret->save();
        }

        return $ret;
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
                'file' => $this->getModulePath() . '/' . $this->getName() . '/migrations/index.php',
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

        if(!$version) {
            $ext = new Core_Extension();
            $ext->name = $this->getName();
            $ext->enabled = $enabled;
            $ext->installed = new Expression('NOW()');
            $ext->classname_id = $this->getClassname(Core_Module::className())->id;

            $ext->save();
            
            $model = new Core_Module();
            $model->extension_id = $ext->id;
            $model->save();
        }
    }
}
