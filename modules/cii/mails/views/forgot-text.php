<?php

use cii\helpers\Html;
use cii\helpers\Url;

?><?= Yii::t('app', 'Dear {name}', ['name' => $user->username]); ?>

<?= Yii::t('app', 'Please follow this link to reset your password:'); ?>
<?= Url::to(['//' . $forgot->getBreadcrumbs(), 'token' => $user->token, 'key' => $user->getAuthKey()], true); ?>