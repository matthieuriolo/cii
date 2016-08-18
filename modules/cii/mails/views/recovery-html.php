<?php

use cii\helpers\Html;
use cii\helpers\Url;

?><h1><?= Yii::t('app', 'Dear {name}', ['name' => $user->username]); ?></h1>

<p><?= Yii::t('app', 'Your password has been reset to {password}', [
    'password' => $password
]); ?></p>