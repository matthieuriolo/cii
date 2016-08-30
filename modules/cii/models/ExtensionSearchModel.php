<?php

namespace app\modules\cii\models;

use Yii;

use cii\base\SearchModel;

use app\modules\cii\models\Classname;
use app\modules\cii\models\Package;
use app\modules\cii\models\Layout;
use app\modules\cii\models\LanguageMessage;

class ExtensionSearchModel extends SearchModel {
    protected $_extensionTypes;
    public function getExtensionsType() {
        if(!$this->_extensionTypes) {
            $this->_extensionTypes = [null => Yii::t('yii', 'All')];


            if($id = Classname::registerModel(Package::className())) {
                $this->_extensionTypes[$id] = Yii::t('yii', 'Package');
            }

            if($id = Classname::registerModel(Layout::className())) {
                $this->_extensionTypes[$id] = Yii::t('yii', 'Layout');
            }

            if($id = Classname::registerModel(LanguageMessage::className())) {
                $this->_extensionTypes[$id] = Yii::t('yii', 'Language Message');
            }
        }

        return $this->_extensionTypes;
    }

    public function extensionTypeFilter($name, $attributes = null) {
        $values = $this->getExtensionsType();

        $this->addAttribute($name, ['extension', 'template' => 'in', 'values' => $values], [
            ['in', 'range' => array_keys($values)]
        ], $attributes);
    }
}