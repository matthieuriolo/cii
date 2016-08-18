<?php
namespace cii\components;

use Yii;
use yii\base\Component;

use app\modules\cii\models\Content;
use app\modules\cii\models\Layout as MLayout;


use yii\helpers\ArrayHelper;

class Layout extends Component {
	public $cache = 'cache';


	public function init() {
		if (is_string($this->cache)) {
            $this->cache = Yii::$app->get($this->cache, false);
        }

        parent::init();
	}
/*
	public function getTemplates($enabled = true, $backend = false) {
		$cacheKey = __CLASS__ . '_contents_' . ($name ?: '');
		if(($data = $this->cache->get($cacheKey)) !== false) {
			return $data;
		}
	}
*
	protected function fetchLayouts() {
		$cacheKey = __CLASS__ . '_getLayoutsForDropdown';
		if(($data = $this->cache->get($cacheKey)) !== false) {
			return $data;
		}

		$data = [];
		foreach(MLayout::find()->all() as $layout) {
			$data[$layout->id] = $layout->name;
		}

		$this->cache->set($cacheKey, $data);
		return $data;
	}
	*/

	public function clearCache() {
		$this->cache->delete(__CLASS__ . '_getLayoutsForDropdown');
		$this->cache->delete(__CLASS__ . '_getContentsForDropdown_yes');
		$this->cache->delete(__CLASS__ . '_getContentsForDropdown_no');
	}

	public function getLayoutsForDropdown($toplevel = true) {
		$cacheKey = __CLASS__ . '_getLayoutsForDropdown';
		if(($data = $this->cache->get($cacheKey)) !== false) {
			return $data;
		}

		$data = [];
		
		if($toplevel) {
			$data[null] = Yii::t('app', 'No selection');
		}

		$data += ArrayHelper::map(MLayout::find()->all(), 'id', 'name');

		$this->cache->set($cacheKey, $data);
		return $data;
	}

	public function getContents($name = null) {
		$cacheKey = __CLASS__ . '_contents_' . ($name ?: '');
		if(($data = $this->cache->get($cacheKey)) !== false) {
			return $data;
		}


		$models = Content::find()
			->with(['contentVisibilities'])
			->where(['contentVisibilities.position' => $name])
			->all();
		$this->cache->set($cacheKey, $models);
		return $models;
	}

	public function getContentTypeValues() {
		return array_keys(Yii::$app->cii->package->getContentTypes());
	}

	public function getContentTypes() {
		return Yii::$app->cii->package->getContentTypes();
	}

	public function getContentTypesForDropdown() {
		return Yii::$app->cii->package->getContentTypes(true);
	}

	public function getContentsForDropdown($toplevel = true) {
		$cacheKey = __CLASS__ . '_getContentsForDropdown_' . ($toplevel ? 'yes' : 'no');
		if(($data = $this->cache->get($cacheKey)) !== false) {
			return $data;
		}

		$ret = [];
		
		if($toplevel) {
			$ret[null] = Yii::t('app', 'No selection');
		}
		
		$models = Content::find()->where(['enabled' => true])->all();
		$ret += ArrayHelper::map($models, 'id', 'name');
		$this->cache->set($cacheKey, $ret);
		return $ret;
	}
}
