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

	protected function prepareSetting($key, $val, $id) {
		$val['class'] = 'cii\base\Configuration';
		$val['key'] = $key;
		$val['id'] = $id;
		return Yii::createObject($val);
	}

	public function getSettingTypes($package = null) {
		if(!is_null($package)) {
			$ret = [];
			if($pkg = $this->getReflection($package)) {
				foreach($pkg->getSettingTypes() as $key => $val) {
					array_push($ret, $this->prepareSetting($key, $val, $pkg->getName()));
				}
			}

			return $ret;
		}

		$cacheKey = __CLASS__ . '_settingTypes';
		if(($data = $this->cache->get($cacheKey)) !== false) {
			return $data;
		}

		$ret = [];

		foreach($this->all() as $pkg) {
			foreach($pkg->getSettingTypes() as $key => $val) {
				array_push($ret, $this->prepareSetting($key, $val, $pkg->id));
			}
		}

		$this->cache->set($cacheKey, $ret);
		return $ret;
	}
}
