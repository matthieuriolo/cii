<?php

use yii\grid\GridView;
use cii\grid\ActionColumn;
use cii\helpers\Html;

$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1>Settings</h1>
    
   
    <?php 
    echo $this->render('_list', [
        'data' => $data,
    ]); ?>
</div>
