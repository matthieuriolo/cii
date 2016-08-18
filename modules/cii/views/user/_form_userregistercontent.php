<?php

use yii\helpers\Html;
use cii\widgets\Toggler;
?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'activate_id')->dropDownList($routesActivate) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'redirect_id')->dropDownList($routes) ?>
    </div>    
</div>

<div class="row">
	<div class="col-md-6">
        <?= $form->field($model, 'login_id')->dropDownList($routesLogin) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'forgot_id')->dropDownList($routesForgot) ?>
    </div>
</div>


