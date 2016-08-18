<?php

use yii\helpers\Html;
use cii\widgets\Toggler;
?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'redirect_id')->dropDownList($routes) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'register_id')->dropDownList($routesRegister) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'forgot_id')->dropDownList($routesForgot) ?>
    </div>
</div>


