<?php

return [
    'logo' => [
        'label' => Yii::t('app', 'Website Logo'),
        'type' => 'image',
        'default' => 'images/logo.png'
    ],

    'favicon' => [
        'label' => Yii::t('app', 'Website favicon'),
        'type' => 'text',
        'default' => 'favicon.ico'
    ],

    'onlylogo' => [
        'label' => Yii::t('app', 'Website only with logo'),
        'type' => 'boolean',
        'default' => false
    ],

    'show_breadcrumb' => [
        'label' => Yii::t('app', 'Show breadcrumb in frontend'),
        'type' => 'boolean',
        'default' => true
    ],

    'navbarcolor' => [
        'label' => Yii::t('app', 'Navbar color'),
        'type' => 'color',
        'default' => '#222222'
    ],

    'navbarcolor_text' => [
        'label' => Yii::t('app', 'Navbar text color'),
        'type' => 'color',
        'default' => '#9d9d9d;'
    ],

    'navbarcolor_text_hover' => [
        'label' => Yii::t('app', 'Navbar hovered text color'),
        'type' => 'color',
        'default' => '#ffffff'
    ],
];