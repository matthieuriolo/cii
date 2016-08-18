<?php

use cii\helpers\Html;
use cii\helpers\Url;

?><?= Yii::t('app', 'Dear {name}', ['name' => $user->username]); ?>

<?= Yii::t('app', 'Your password have been changed. If you have not changed your password, please contact the webmaster!'); ?>
