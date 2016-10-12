<?php

return [
    'logo' => [
        'label' => Yii::l('cii', 'Website Logo'),
        'type' => 'image',
        'default' => 'images/logo.png'
    ],

    'favicon' => [
        'label' => Yii::l('cii', 'Website favicon'),
        'type' => 'text',
        'default' => 'favicon.ico'
    ],

    'onlylogo' => [
        'label' => Yii::l('cii', 'Website only with logo'),
        'type' => 'boolean',
        'default' => false
    ],

    'show_breadcrumb' => [
        'label' => Yii::l('cii', 'Show breadcrumb in frontend'),
        'type' => 'boolean',
        'default' => true
    ],

    'show_copyright' => [
        'label' => Yii::l('cii', 'Show copyright in footer'),
        'type' => 'boolean',
        'default' => true
    ],

    'navbarcolor' => [
        'label' => Yii::l('cii', 'Navbar background color'),
        'type' => 'color',
        'default' => '#222222'
    ],

    'navbarcolor_text' => [
        'label' => Yii::l('cii', 'Navbar text color'),
        'type' => 'color',
        'default' => '#FFFFFF;'
    ],

    'navbarcolor_link' => [
        'label' => Yii::l('cii', 'Navbar link color'),
        'type' => 'color',
        'default' => '#9d9d9d;'
    ],

    'navbarcolor_link_hover' => [
        'label' => Yii::l('cii', 'Navbar hovered link color'),
        'type' => 'color',
        'default' => '#ffffff'
    ],


    'footercolor' => [
        'label' => Yii::l('cii', 'Footer background color'),
        'type' => 'color',
        'default' => '#f5f5f5'
    ],

    'footercolor_text' => [
        'label' => Yii::l('cii', 'Footer text color'),
        'type' => 'color',
        'default' => '#333333'
    ],

    'footercolor_link' => [
        'label' => Yii::l('cii', 'Footer link color'),
        'type' => 'color',
        'default' => '#337ab7'
    ],

    'footercolor_link_hover' => [
        'label' => Yii::l('cii', 'Footer text color'),
        'type' => 'color',
        'default' => '#23527c'
    ],
];