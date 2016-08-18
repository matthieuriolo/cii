<?php

use cii\helpers\Html;
use cii\helpers\Url;

?><h1><?= Yii::t('app', 'Dear {name}', ['name' => $user->username]); ?></h1>

<p><?= Yii::t('app', 'Your email address has been changed. If you have not changed your email address, please contact the webmaster!'); ?></p>