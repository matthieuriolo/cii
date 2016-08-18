<?php

use cii\helpers\Html;
use cii\helpers\Url;

?><?= Yii::t('app', 'Dear {name}', ['name' => $user->username]); ?>

<?= Yii::t('app', 'Your email address has been changed. If you have not changed your email address, please contact the webmaster!'); ?>
