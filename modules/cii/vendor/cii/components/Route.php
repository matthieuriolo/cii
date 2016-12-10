<?php
namespace cii\components;

use Yii;
use yii\base\Component;
use app\modules\cii\models\common\Route as MRoute;
use app\modules\cii\models\common\CountAccess;

class Route extends Component {
	public $cache = 'cache';

	public function init() {
		if (is_string($this->cache)) {
            $this->cache = Yii::$app->get($this->cache, false);
        }

        parent::init();
	}

	public function clearCache() {
		$this->cache->delete(__CLASS__ . '_getRoutesForDropdown_yes');
		$this->cache->delete(__CLASS__ . '_getRoutesForDropdown_no');
		$this->cache->delete(__CLASS__ . '_typeValues');
	}

	public function flushStatistics() {
		return MRoute::updateAll(['hits' => 0])
			+ CountAccess::deleteAll();
	}

	public function getTypes() {
		return Yii::$app->cii->package->getRouteTypes();
	}

	public function getTypeValues() {
		$cacheKey = get_called_class() . '_typeValues';
		if(($data = $this->cache->get($cacheKey)) !== false) {
			return $data;
		}

		$ret = array_keys($this->getTypes());
		$this->cache->set($cacheKey, $ret);
		return $ret;
	}

	public function getTypesForDropdown() {
		return Yii::$app->cii->package->getRouteTypes(true);
	}
}
