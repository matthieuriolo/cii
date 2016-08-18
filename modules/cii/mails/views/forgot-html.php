<?php

use cii\helpers\Html;
use cii\helpers\Url;

?><h1><?= Yii::t('app', 'Dear {name}', ['name' => $user->username]); ?></h1>

<p><?= Yii::t('app', 'Please follow the {link} to reset your password', [
    'link' => Html::a(
        Yii::t('app', 'link'),
        Url::to([
            '//' . $forgot->getBreadcrumbs(),
            'token' => $user->token,
            'key' => $user->getAuthKey()
        ], true))
]); ?></p>