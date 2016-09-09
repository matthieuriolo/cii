<?php

namespace app\layouts\cii\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle {
    public $sourcePath = '@app/layouts/cii/assets/web';

    public $css = [
        'css/site.css',
        'css/bootstrap-wysihtml5.css',
    ];

    public $js = [
        'js/tabhack.js',
        'js/rivets.min.js',
        'js/moment-with-locales.js',
        //['js/require.min.js', 'data-main' => 'js/app/init.js']
    ];

    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    static public function register($view) {
        $info = parent::register($view);
        $info->js[] = ['js/require.min.js', 'data-main' => $info->baseUrl . '/js/app/init.js'];
        return $info;
    }
}
