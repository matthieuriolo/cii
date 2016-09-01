<?php
namespace cii\components;

use Yii;
use yii\base\Component;
use app\modules\cii\models\Language as MLanguage;
use yii\base\InvalidConfigException;


use cii\base\LanguageReflection;

class Language extends Component {
	public $cache = 'cache';
	public $session = 'session';

	public function init() {
		if(is_string($this->cache)) {
            $this->cache = Yii::$app->get($this->cache, false);
        }

        if(is_string($this->session)) {
            $this->session = Yii::$app->get($this->session, false);
        }

        parent::init();
	}

	public function getExtensionType() {
		return 'language';
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

	public function getActiveLanguage() {
		if($language = $this->session->get('activeLanguage')) {
			return MLanguage::findOne($language);
		}
		
		if(($user = Yii::$app->user->getIdentity()) && ($language = $user->language)) {
			return $language;
		}

		if($language = Yii::$app->cii->package->setting('cii', 'language')) {
			return MLanguage::findOne($language);
		}

		return null;
	}

	public function setActiveLanguage($language) {
		if(is_object($language)) {
			if(!($language instanceof MLanguage)) {
				throw new InvalidConfigException();
			}

			$language = $language->id;
		}

		$this->session->set('activeLanguage', $language);
	}


	public function getSettingTypes($code, $type, $name) {
		return [];
	}

	public function getReflection($code, $type, $name) {
		$pkg = new LanguageReflection();
		if($pkg->loadLanguage($code, $type, $name)) {
			return $pkg;
		}
		
		return null;
	}
}
