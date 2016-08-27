<?php

namespace app\modules\cii\models;

use Yii;

class LanguageMessage extends BaseExtension {
    public static function tableName() {
        return '{{%Cii_LanguageMessages}}';
    }
    

    public function getTranslatedExtension() {
        return $this->owner->hasOne(Extension::className(), ['id' => 'translatedExtension_id']);
    }

    public function getLanguage() {
        return $this->owner->hasOne(Language::className(), ['id' => 'language_id']);
    }

    public function getReflection() {
        return Yii::$app->cii->language->getReflection(
            $this->language->code,
            $this->translatedExtension->getReflection()->getType(),
            $this->translatedExtension->name
        );
    }
    
    public function getSettings() {
	    return Yii::$app->cii->language->getSettingTypes(
            $this->language->code,
            $this->translatedExtension->getReflection()->getType(),
            $this->translatedExtension->name
        );
	}

    static public function getTypename() {
        return Yii::t('app', 'Language Message');
    }
}
