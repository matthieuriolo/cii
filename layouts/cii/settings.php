<?php

return [
    'logo' => [
        'label' => Yii::t('app', 'Website Logo'),
        'type' => 'image'
    ],

    'onlylogo' => [
        'label' => Yii::t('app', 'Website only logo in backend'),
        'type' => 'boolean',
        'default' => false
    ],

    'show_breadcrumb' => [
        'label' => Yii::t('app', 'Show breadcrumb in frontend'),
        'type' => 'boolean',
        'default' => true
    ],

    'navbarcolor' => [
        'label' => Yii::t('app', 'Navbar color in frontend'),
        'type' => 'color',
        'default' => '#222222'
    ],
];