<?php
namespace cii\components;

use Yii;
use yii\base\Component;
use app\modules\cii\models\Route as MRoute;

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
	}

	public function getTypeValues() {
		return array_keys($this->getTypes());
	}
	
	public function getTypes() {
		return Yii::$app->cii->package->getRouteTypes();
	}

	public function getTypesForDropdown() {
		return Yii::$app->cii->package->getRouteTypes(true);
	}

	public function getRoutesForDropdown($toplevel = true) {
		$ret = [];
		if($toplevel) {
			$ret[null] = Yii::t('app', 'No selection');
		}

		return $ret + $this->fetchRoutes(false);
	}

	public function getParentRoutesForDropdown($toplevel = true) {
		$ret = $this->fetchRoutes();
		
		if($toplevel) {
			$data = [
				Yii::t('app', 'Top Level') => [
					null => Yii::t('app', 'No parent')
				],
			];

			if(count($ret)) {
				$data[Yii::t('app', 'Routes')] = $ret;
			}

			$ret = $data;
		}

		return $ret;
	}

	protected function fetchRoutes($onlyAllowedChildren = true) {
		$cacheKey = __CLASS__ . '_getRoutesForDropdown_'. ($onlyAllowedChildren ? 'yes' : 'no');
		if(($data = $this->cache->get($cacheKey)) !== false) {
			return $data;
		}

		$data = $this->fetchSubRoutes($onlyAllowedChildren);

		$this->cache->set($cacheKey, $data);
		return $data;
	}

	protected function fetchSubRoutes($onlyAllowedChildren, $parentId = null, $lvl = 1) {
		$routes = MRoute::find()->where(['parent_id' => $parentId])->all();

		$ret = [];

		foreach($routes as $route) {
			//we silently fetch the error
			try {
				if($onlyAllowedChildren && !$route->outbox()->allowChildren) {
					continue;
				}
			}catch(\Exception $e) {
				continue;
			}

			$ret[$route->id] = str_pad('', $lvl, '-', STR_PAD_LEFT) . ' ' . $route->slug;

			if($route->hasChildren()) {
				$sub = $this->fetchSubRoutes($onlyAllowedChildren, $route->id, $lvl + 1);
				foreach($sub as $key => $value) {
					$ret[$key] = $value;
				}
			}
		}

		return $ret;
	}
}
