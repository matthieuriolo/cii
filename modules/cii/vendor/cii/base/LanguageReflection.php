<?php


namespace cii\base;

use Yii;
use app\modules\cii\models\LanguageMessage;
use yii\base\InvalidConfigException;

class LanguageReflection extends BaseReflection {
	public function load($dir) {
		if(parent::load($dir)) {
			if(isset($this->data['messageType'], $this->data['language'])) {
				return true;
			}
		}

		return false;
	}

	public function loadLanguage($code, $type, $name) {
		return $this->load($this->getInstallationPath() . '/' . $type . 's/' . $code . '/' . $name);
	}

	public function loadByName($name) {
        throw new InvalidConfigException();
    }

	public function getMessageType() {
		return $this->data['messageType'];
	}

	public function getLanguage() {
		return $this->data['language'];
	}

    protected function getInstallationPath() {
        return Yii::getAlias(Yii::$app->messagePath);
    }
    
    protected function getExtensionClassName() {
        return LanguageMessage::className();
    }
}
