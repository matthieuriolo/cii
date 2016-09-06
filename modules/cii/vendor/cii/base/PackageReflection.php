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
    protected function getInstallationPath() {
        return Yii::getAlias(Yii::$app->modulePath);
    }
    
    
    protected function getExtensionClassName() {
        return  Core_Module::className();
    }

    protected function migrate($enabled = false) {
        $version = $this->getInstalledVersion();
        parent::migrate($enabled);


        $class = [
            'class' => null,
            'file' => null
        ];

        if(!$version) {
            //no earlier version installed
            $class = [
                'class' => 'app\\modules\\' . $this->getName() . '\\migrations\\Index',
                'file' => $this->basePath . '/' . $this->getName() . '/migrations/index.php',
            ];
        }else {
            //found a version, test if uptodate or needs migration
            $refl = new PackageReflection();
            if($refl->loadByName($this->getName())) {
                if(version_compare($refl->getVersion(), $this->getVersion(), '<')) {
                    $name = str_replace('.', '_', $refl->getVersion()) .
                        '_migrateto_' .
                        str_replace('.', '_', $this->getVersion());

                    $class['file'] = $this->getInstallationPath() . '/' .
                        $this->getName() . 
                        '/migrations/' . 
                        $name
                    ;
                    $class['class'] = $name;
                }
            }
        }

        if($class['class'] && is_file($class['file'])) {
            require_once($class['file']);
            $c = $class['class'];
            $migrator = new $c();
            $migrator->up();
        }
    }
}
