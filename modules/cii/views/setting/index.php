<?php

use yii\grid\GridView;
use cii\grid\ActionColumn;
use cii\helpers\Html;

$this->title = Yii::p('cii', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title); ?></h1>


<?php 
echo $this->render('_list', [
    'data' => $data,
    'model' => $model,
    'showExtension' => true
]); ?>
