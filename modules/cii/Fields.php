<?php 

namespace app\modules\cii;
use Yii;


class Fields {
    static public function getFieldTypes() {
        return [
            'text' => 'cii\fields\TextField',
            'textarea' => 'cii\fields\TextareaField',
            'html' => 'cii\fields\HtmlField',
            'password' => 'cii\fields\PasswordField',
            'texteditor' => 'cii\fields\TexteditorField',
            
            'captcha' => 'cii\fields\CaptchaField',
            

            'file' => 'cii\fields\file\FileField',
            'favicon' => 'cii\fields\file\FaviconField',
            'image' => 'cii\fields\file\ImageField',
            'audio' => 'cii\fields\file\AudioField',
            'movie' => 'cii\fields\file\MovieField',

            'boolean' => 'cii\fields\BooleanField',
            
            'email' => 'cii\fields\EmailField',
            'color' => 'cii\fields\ColorField',
            'url' => 'cii\fields\UrlField',
            'integer' => 'cii\fields\IntegerField',
            'float' => 'cii\fields\FloatField',
            
            'datetime' => 'cii\fields\DatetimeField',
            'date' => 'cii\fields\DateField',
            'time' => 'cii\fields\TimeField',

            'in' => 'cii\fields\dropdown\InField',
            
            'extension' => 'cii\fields\select\ExtensionField',
            'language' => 'cii\fields\select\LanguageField',
            'package' => 'cii\fields\select\PackageField',
            'route' => 'cii\fields\select\RouteField',
            'content' => 'cii\fields\select\ContentField',
            
            'group' => 'cii\fields\select\GroupField',
            'user' => 'cii\fields\select\UserField',

            'routetypes' => 'cii\fields\select\RouteTypesField',
            'contenttypes' => 'cii\fields\select\ContentTypesField',
            //'fieldtypes' => 'cii\fields\array\FieldTypesField',
            'positiontypes' => 'cii\fields\select\PositionTypesField',
        ];
    }
}