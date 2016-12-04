<?php

return [
    'name' => [
        'label' => Yii::p('cii', 'Website name'),
        'type' => 'text',
        'default' => 'My website'
    ],

    /*
    'favicon' => [
        'label' => Yii::p('cii', 'Website Logo'),
        'type' => 'favicon'
    ],

    'timezone' => [
        'label' => Yii::p('cii', 'Website default timezone'),
        'type' => 'in',
        'default' => 'UTC'
    ],
    */

    'resize_uploaded_image' => [
        'label' => Yii::p('cii', 'Uploaded resized images'),
        'type' => 'boolean',
        'default' => true
    ],

    'size_uploaded_image' => [
        'label' => Yii::p('cii', 'Maximal size for uploaded images'),
        'type' => 'integer',
        'default' => 1200
    ],

    'offline' => [
        'label' => Yii::p('cii', 'Website offline'),
        'type' => 'boolean',
        'default' => false
    ],

    'offline_description' => [
        'label' => Yii::p('cii', 'Website description'),
        'type' => 'texteditor',
        'default' => Yii::p('cii', 'The website is currently offline')
    ],

    'startroute' => [
        'label' => Yii::p('cii', 'Route showed at startpage'),
        'type' => 'route',
    ],

    'multilanguage' => [
        'label' => Yii::p('cii', 'Website is multilanguage'),
        'type' => 'boolean',
        'default' => false
    ],

    'language' => [
        'label' => Yii::p('cii', 'Website language'),
        'type' => 'language',
        //'values' => Yii::$app->cii->language->getLanguagesForDropdown()
    ],


    'layout' => [
        'label' => Yii::p('cii', 'Default Layout'),
        'default' => 'cii',
        'type' => 'in',
        'values' => Yii::$app->cii->layout->getLayoutsForDropdown()
    ],

    'mail_layout' => [
        'label' => Yii::p('cii', 'Mail layout'),
        'default' => 'cii',
        'type' => 'in',
        'values' => Yii::$app->cii->layout->getLayoutsForDropdown()
    ],

    'metakeys' => [
        'label' => Yii::p('cii', 'Website metakeys'),
        'type' => 'text',
        'default' => 'Cii, CMS'
    ],

    'metadescription' => [
        'label' => Yii::p('cii', 'Website description'),
        'type' => 'text',
        'default' => 'A CMS Website created with Cii'
    ],

    'rememberduration' => [
        'label' => Yii::p('cii', 'Remember login duration'),
        'type' => 'integer',
        'default' => 3600 * 24 * 30
    ],

    'transport.type' => [
        'label' => Yii::p('cii', 'Mail Transport type'),
        'type' => 'in',
        'default' => 'file',
        'values' => [
            'file' => Yii::p('cii', 'File'),
            'sendmail' => Yii::p('cii', 'Local mail system'),
            'smtp' => Yii::p('cii', 'SMTP'),
        ]
    ],

    'transport.smtp.host' => [
        'label' => Yii::p('cii', 'SMTP Host'),
        'type' => 'text'
    ],

    'transport.smtp.user' => [
        'label' => Yii::p('cii', 'SMTP User'),
        'type' => 'text'
    ],

    'transport.smtp.password' => [
        'label' => Yii::p('cii', 'SMTP Password'),
        'type' => 'password'
    ],

    'transport.smtp.port' => [
        'label' => Yii::p('cii', 'SMTP Port'),
        'type' => 'integer',
        'default' => 465
    ],

    'transport.smtp.encryption' => [
        'label' => Yii::p('cii', 'SMTP Encryption'),
        'type' => 'in',
        'values' => [
            'ssl' => Yii::p('cii', 'SSL'),
            'tls' => Yii::p('cii', 'TLS'),
        ],
        'default' => 'ssl'
    ],

    'sender' => [
        'label' => Yii::p('cii', 'Sending address'),
        'type' => 'email',
        'default' => 'my@website.local'
    ],
];