<?php
namespace cii\helpers;
use Yii;

class Html extends \yii\helpers\BaseHtml {
    public static function languageLink($model) {
        if($model && $model->language_id) {
            return static::a($model->language->name, [
                Yii::$app->seo->relativeAdminRoute('modules/cii/language/view'),
                'id' => $model->language_id
            ]);
        }

        return null;
    }

	public static function a($text, $url = null, $options = []){
        if ($url !== null) {
            $options['href'] = Url::to($url);
        }
        
        return static::tag('a', $text, $options);
    }



    public static function boolean($value){
        $class = 'glyphicon-ok text-success';
        if(!$value) {
        	$class = 'glyphicon-remove text-danger';
        }

        return '<i class="glyphicon ' . $class . '"></i>';
    }
}
