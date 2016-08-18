<?php

use cii\helpers\Html;
use cii\helpers\Url;

?><?= Yii::t('app', 'Dear {name}', ['name' => $user->username]); ?>

<?= Yii::t('app', 'Your password has been reset to {password}', [
    'password' => $password
]); ?>