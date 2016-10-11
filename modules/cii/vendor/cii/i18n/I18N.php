<?php
namespace cii\i18n;

use Yii;

class I18N extends \yii\i18n\I18N {
    public $packageSource;
    public $layoutSource;

    public function init() {
        parent::init();

        if(!$this->packageSource) {
            $this->packageSource = Yii::createObject([
                'class' => 'cii\i18n\PackageMessageSource',
                'sourceLanguage' => Yii::$app->sourceLanguage,
            ]);
        }

        if(!$this->layoutSource) {
            $this->layoutSource = Yii::createObject([
                'class' => 'cii\i18n\LayoutMessageSource',
                'sourceLanguage' => Yii::$app->sourceLanguage,
            ]);
        }
    }
    
    /*
    public function translate($category, $message, $params, $language) {
        if(is_array($category)) {
            $category = $category[1];
        }

        return parent::translate($category, $message, $params, $language);
    }*/

    public function getMessageSource($category) {
        if(is_array($category)) {
            list($type, $cat) = $category;
            if($type == 'package') {
                return $this->packageSource;
            }else if($type == 'layout') {
                return $this->layoutSource;
            }

            return $this->layoutSource;
        }

        return parent::getMessageSource($category);
    }
}
