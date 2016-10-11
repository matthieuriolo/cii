<?php
namespace cii\i18n;

use Yii;

class BaseMessageSource extends \yii\i18n\PhpMessageSource {
    protected function getMessageFilePath($category, $language) {
        $messageFile = Yii::getAlias($this->basePath) . "/$language/$category/translation.php";
        return $messageFile;
    }

    public function translate($category, $message, $language) {
        $category = $category[1];
        return parent::translate($category, $message, $language);
    }
}
