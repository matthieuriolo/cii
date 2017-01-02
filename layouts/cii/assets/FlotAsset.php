<?php

namespace app\layouts\cii\assets;

use yii\web\AssetBundle;

class FlotAsset extends AssetBundle {
    public $sourcePath = '@app/layouts/cii/assets/flot';

    public $js = [
        'jquery.flot.js',
        'excanvas.min.js',
        'jquery.colorhelpers.js',
        'jquery.flot.canvas.js',
        'jquery.flot.resize.js',
        'jquery.flot.pie.js',
        'jquery.flot.image.js',
        'jquery.flot.time.js',
    ];

    public $depends = [
        'app\layouts\cii\assets\AppAsset'
    ];
}
