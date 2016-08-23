<?php

use yii\helpers\Html;
use cii\widgets\DetailView;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        'code',
        'shortcode'
        'enabled:boolean'
    ],
]) ?>

<hr>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'time',
        'date',
        'datetime',
    ],
]) ?>

<hr>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'thousandSeparator',
        'decimalSeparator',
        'decimals',
        'removeZeros:boolean'
    ],
]) ?>


<hr>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'currencySymbol',
        'currencySymbolPlace:boolean',
        'currencySmallestUnit',
        'currencySmallestUnit:boolean'
    ],
]) ?>