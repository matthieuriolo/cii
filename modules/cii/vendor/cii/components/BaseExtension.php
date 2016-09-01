<?php
namespace cii\components;

use Yii;
use yii\base\Component;

abstract class BaseExtension extends Component {
	public $cache = 'cache';

	public function init() {
		if(is_string($this->cache)) {
            $this->cache = Yii::$app->get($this->cache, false);
        }
        parent::init();
	}

	abstract public function all($enabled = true);
	abstract public function getReflection($name);
	abstract public function getExtensionType();


	public function setting($package, $key, $defaultValue = null) {
		if(func_num_args() == 2) {
			return Yii::$app->cii->setting($this->extensionType, $package, $key);
		}
		return Yii::$app->cii->setting($this->extensionType, $package, $key, $defaultValue);
	}

	protected function prepareSetting($key, $val, $id) {
		$val['class'] = 'cii\base\Configuration';
		$val['key'] = $key;
		$val['id'] = $id;
		$val['extension_type'] = $this->getExtensionType();
		return Yii::createObject($val);
	}

	public function getSettingTypes($package = null) {
		if(!is_null($package)) {
			$ret = [];
			if($pkg = $this->getReflection($package)) {
				foreach($pkg->getSettingTypes() as $key => $val) {
					//array_push($ret, $this->prepareSetting($key, $val, $pkg->getName()));
					$ret[$key] = $this->prepareSetting($key, $val, $pkg->getName());
				}
			}

			return $ret;
		}
		
		$cacheKey = get_called_class() . '_settingTypes';
		if(($data = $this->cache->get($cacheKey)) !== false) {
			return $data;
		}

		$ret = [];
		foreach($this->all() as $pkg) {
			foreach($pkg->getSettingTypes() as $key => $val) {
				if(is_object($val)) {
					$ret[] = $val;
				}else {
					$ret[] = $this->prepareSetting($key, $val, $pkg->id);
				}
			}
		}

		$this->cache->set($cacheKey, $ret);
		return $ret;
	}
}
