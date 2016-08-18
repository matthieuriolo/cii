<?php
namespace cii\components;

use Yii;
use yii\base\Component;
use app\modules\cii\models\Language as MLanguage;

class Language extends Component {
	public $cache = 'cache';

	public function init() {
		if (is_string($this->cache)) {
            $this->cache = Yii::$app->get($this->cache, false);
        }

        parent::init();
	}

	public function clearCache() {
		$this->cache->delete(__CLASS__ . '_getLanguagesForDropdown');
	}

	public function getLanguagesForDropdown($toplevel = true) {
		if($toplevel) {
			$data = [
				null => Yii::t('app', 'No selection')
			];
			$data += $this->fetchLanguages();
		}else {
			$data = $this->fetchLanguages();
		}

		return $data;
	}

	protected function fetchLanguages() {
		$cacheKey = __CLASS__ . '_getLanguagesForDropdown';
		if(($data = $this->cache->get($cacheKey)) !== false) {
			return $data;
		}

		$data = [];
		foreach(MLanguage::find()->all() as $language) {
			$data[$language->id] = $language->name;
		}

		$this->cache->set($cacheKey, $data);
		return $data;
	}
}
