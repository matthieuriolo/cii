<?php
use cii\widgets\Toggler;
echo Toggler::widget([
    'model' => $model,
    'property' => 'value',
    'form' => $form
]); ?>