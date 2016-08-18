<?php

use cii\helpers\Html;
use cii\helpers\Url;

?><?= Yii::t('app', 'Dear {name}', ['name' => $user->username]); ?>

<?= Yii::t('app', 'Thank you for your registration! That you are able to use this account you have to first activate it. Please follow this link:'); ?>
<?= Url::to(['//' . $activate->getBreadcrumbs(), 'token' => $user->token, 'key' => $user->getAuthKey()], true); ?>