<?php

return [
    'name' => [
        'label' => Yii::t('app', 'Website name'),
        'type' => 'text',
        'default' => 'My website'
    ],

    /*
    'favicon' => [
        'label' => Yii::t('app', 'Website Logo'),
        'type' => 'favicon'
    ],

    'timezone' => [
        'label' => Yii::t('app', 'Website default timezone'),
        'type' => 'in',
        'default' => 'UTC'
    ],
    */

    'multilanguage' => [
        'label' => Yii::t('app', 'Website is multilanguage'),
        'type' => 'boolean',
        'default' => false
    ],

    'language' => [
        'label' => Yii::t('app', 'Website language'),
        'type' => 'in',
        'values' => Yii::$app->cii->language->getLanguagesForDropdown()
    ],


    'layout' => [
        'label' => Yii::t('app', 'Default Layout'),
        'default' => 'cii',
        'type' => 'in',
        'values' => Yii::$app->cii->layout->getLayoutsForDropdown()
    ],

    'mail_layout' => [
        'label' => Yii::t('app', 'Mail layout'),
        'default' => 'cii',
        'type' => 'in',
        'values' => Yii::$app->cii->layout->getMailLayoutsForDropdown()
    ],

    'metakeys' => [
        'label' => Yii::t('app', 'Website metakeys'),
        'type' => 'text',
        'default' => 'Cii, CMS'
    ],

    'metadescription' => [
        'label' => Yii::t('app', 'Website description'),
        'type' => 'text',
        'default' => 'A CMS Website created with Cii'
    ],

    'rememberduration' => [
        'label' => Yii::t('app', 'Remember login duration'),
        'type' => 'integer',
        'default' => 3600 * 24 * 30
    ],

    'transport.type' => [
        'label' => Yii::t('app', 'Mail Transport type'),
        'type' => 'in',
        'default' => 'file',
        'values' => [
            'file' => Yii::t('app', 'File'),
            'sendmail' => Yii::t('app', 'Local mail system'),
            'smtp' => Yii::t('app', 'SMTP'),
        ]
    ],

    'transport.smtp.host' => [
        'label' => Yii::t('app', 'SMTP Host'),
        'type' => 'text'
    ],

    'transport.smtp.user' => [
        'label' => Yii::t('app', 'SMTP User'),
        'type' => 'text'
    ],

    'transport.smtp.password' => [
        'label' => Yii::t('app', 'SMTP Password'),
        'type' => 'password'
    ],

    'transport.smtp.port' => [
        'label' => Yii::t('app', 'SMTP Port'),
        'type' => 'integer',
        'default' => 465
    ],

    'transport.smtp.encryption' => [
        'label' => Yii::t('app', 'SMTP Encryption'),
        'type' => 'in',
        'values' => [
            'ssl' => Yii::t('app', 'SSL'),
            'tls' => Yii::t('app', 'TLS'),
        ],
        'default' => 'ssl'
    ],

    'sender' => [
        'label' => Yii::t('app', 'Sending address'),
        'type' => 'email',
        'default' => 'my@website.local'
    ],
];