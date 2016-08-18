<?php

use cii\helpers\Html;
use cii\helpers\Url;

?><h1><?= Yii::t('app', 'Dear {name}', ['name' => $user->username]); ?></h1>

<p><?= Yii::t('app', 'Thank you for your registration! That you are able to use this account you have to first activate it. Please follow the this {link}', [
    'link' => Html::a(
        Yii::t('app', 'link'),
        Url::to([
            '//' . $activate->getBreadcrumbs(),
            'token' => $user->token,
            'key' => $user->getAuthKey()
        ], true))
]); ?></p>