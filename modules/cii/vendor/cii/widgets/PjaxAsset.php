<?php
namespace cii\widgets;

use yii\web\AssetBundle;

class PjaxAsset extends AssetBundle {
    public $sourcePath = '@cii/yii2-pjax';
    public $js = [
        'jquery.pjax.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
