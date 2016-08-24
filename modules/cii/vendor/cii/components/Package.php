<?php
namespace cii\components;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\cii\models\Package as MPackage;
use cii\web\controllers\BackendCon;
use cii\base\PackageReflection;

class Package extends BaseExtension {
	public function getReflection($name) {
		$pkg = new PackageReflection();
		if($pkg->loadByName($name)) {
			return $pkg;
		}
		
		return null;
	}

	

	public function clearCache() {
		$this->cache->delete(__CLASS__ . '_namesFromDB_all');
		$this->cache->delete(__CLASS__ . '_namesFromDB_yes');
		$this->cache->delete(__CLASS__ . '_namesFromDB_no');
		
		$this->cache->delete(__CLASS__ . '_routeTypes_yes');
		$this->cache->delete(__CLASS__ . '_routeTypes_no');
		$this->cache->delete(__CLASS__ . '_contentTypes_yes');
		$this->cache->delete(__CLASS__ . '_contentTypes_no');
		$this->cache->delete(__CLASS__ . '_permissionTypes_yes');
		$this->cache->delete(__CLASS__ . '_permissionTypes_no');

		$this->cache->delete(__CLASS__ . '_settingTypes');
	}

	public function moduleInitializerList() {
		$names = $this->namesFromDB(true);
		$ret = [];
		
		foreach($names as $name) {
			$class = 'app\modules\\' . $name . '\Module';
			if(class_exists($class)) {
				$ret[$name] = ['class' => $class];
			}
		}

		return $ret;
	}
	
	protected function namesFromDB($enabled = null) {
		$cacheKey = __CLASS__ . '_namesFromDB_' . (is_null($enabled) ? 'all' : ($enabled ? 'yes' : 'no'));
		if(($data = $this->cache->get($cacheKey)) !== false) {
			return $data;
		}
		
		$module = MPackage::find();
		if(!is_null($enabled)) {
			$module = $module->joinWith('extension as extension')->where(['extension.enabled' => $enabled]);
		}

		$data = $module->all();
		$data = ArrayHelper::getColumn($data, 'name');
		$this->cache->set($cacheKey, $data);
		return $data;
	}
	
	public function all($enabled = true) {
		$data = $this->namesFromDB($enabled);
		$data = array_map(function($name) {
			$class = 'app\modules\\' . $name . '\Module';
			
			if(class_exists($class)) {
				$module = Yii::createObject(['class' => $class], [$name, Yii::$app]);
        		$module->setInstance($module);
        		return $module;
			}

			return null;
		}, $data);

		
		$data = array_filter($data, function($pkg) {
			if($pkg instanceof \cii\backend\Package) {
				return true;
			}

			return false;
		});

		return $data;
	}


	public function getRouteTypes($dropdown = false) {
		$cacheKey = __CLASS__ . '_routeTypes_' . ($dropdown ? 'yes' : 'no');
		if(($data = $this->cache->get($cacheKey)) !== false) {
			return $data;
		}

		$ret = [];

		if($dropdown) {
			$ret[] = Yii::t('app', 'No selection');
		}


		foreach($this->all() as $pkg) {
			if($dropdown) {
				$types = $pkg->getRouteTypes();
				if(count($types)) {
					$ret[$pkg->getDisplayName()] = $types;
				}
			}else {
				$ret += $pkg->getRouteTypes();
			}
		}

		$this->cache->set($cacheKey, $ret);
		return $ret;
	}

	public function getContentTypes($dropdown = false) {
		$cacheKey = __CLASS__ . '_contentTypes_' . ($dropdown ? 'yes' : 'no');
		if(($data = $this->cache->get($cacheKey)) !== false) {
			return $data;
		}

		$ret = [];

		if($dropdown) {
			$ret[] = Yii::t('app', 'No selection');
		}
		
		foreach($this->all() as $pkg) {
			if($dropdown) {
				$types = $pkg->getContentTypes();
				if(count($types)) {
					$ret[$pkg->getDisplayName()] = $types;
				}
			}else {
				$ret += $pkg->getContentTypes();
			}
		}

		$this->cache->set($cacheKey, $ret);
		return $ret;
	}


	public function getPermissionTypes($dropdown = false) {
		$cacheKey = __CLASS__ . '_permissionTypes_' . ($dropdown ? 'yes' : 'no');
		if(($data = $this->cache->get($cacheKey)) !== false) {
			return $data;
		}

		$ret = [];

		if($dropdown) {
			$ret[] = Yii::t('app', 'No selection');
		}


		foreach($this->all() as $pkg) {
			$types = [];
			foreach($pkg->getPermissionTypes() as $key => $val) {
				$types[$pkg->id . '-' . $key] = $val;
			}
			
			if($dropdown) {
				if(count($types)) {
					$ret[$pkg->getDisplayName()] = $types;
				}
			}else {
				$ret += $types;
			}
		}

		$this->cache->set($cacheKey, $ret);
		return $ret;
	}
}
