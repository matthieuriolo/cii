<?php

use yii\helpers\Html;
use cii\widgets\Toggler;
?>

<div class="row">
    <div class="col-md-6">
        <?= Toggler::widget([
            'model' => $model,
            'property' => 'show_groups',
            'form' => $form
        ]); ?>
    </div>
</div>


