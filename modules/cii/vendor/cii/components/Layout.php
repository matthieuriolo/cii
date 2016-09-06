<?php
namespace cii\components;

use Yii;
use app\modules\cii\models\Content;
use app\modules\cii\models\ContentVisibilities;
use app\modules\cii\models\Layout as MLayout;

use cii\base\LayoutReflection;

use yii\helpers\ArrayHelper;

class Layout extends BaseExtension {
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
	
	public function getExtensionType() {
		return 'layout';
	}
	
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

		$data += ArrayHelper::map($this->all(), 'id', 'name');

		$this->cache->set($cacheKey, $data);
		return $data;
	}

	public function all($enabled = true) {
		$query = MLayout::find();
		if(!is_null($enabled)) {
			$query = $query
				->joinWith('extension as extension')
				->where(['extension.enabled' => $enabled]);
		}

		return $query->all();
	}

	public function getContents($name = null, $routeId = true) {
		if($routeId === true) {
			$routeId = null;

			if(Yii::$app->seo && ($mainRoute = Yii::$app->seo->getCalledModelRoute())) {
				$routeId = $mainRoute->id;
			}
		}

		$where = [
			'position' => $name,
			'ext.enabled' => true
		];

		if(is_int($routeId) || is_null($routeId)) {
			$where['route_id'] = $routeId;
		}


		$models = ContentVisibilities::find()
			->joinWith([
				'content.classname.package.extension as ext'
			])
			->where($where)
			->all()
		;


		//outbox
		$models = array_map(function($model) {
			return $model->content->outbox();
		}, $models);
		
		//check if visible
		$models = array_filter($models, function($model) {
			$info = $model->getShadowInformation();
			$controller = Yii::$app->createController($info['route'])[0];
			return $controller->$info['isVisible']($model);
		});

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


	public function getReflection($name) {
		$pkg = new LayoutReflection();
		if($pkg->loadByName($name)) {
			return $pkg;
		}
		
		return null;
	}


	public function getBackendLayoutsForDropdown() {
		return [];
	}

	public function getFrontendLayoutsForDropdown() {
		return [];
	}

	public function getMailLayoutsForDropdown() {
		return [];
	}

	public function getPositionsForDropdown() {
		$data = [null => Yii::t('app', 'No selection')];
		$refl = $this->getReflection(Yii::$app->cii->package->setting('cii', 'layout'));

		return $data + $refl->getPositions();
	}
}
