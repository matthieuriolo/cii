<?php

use yii\bootstrap\Tabs;
use app\modules\cii\Permission;


$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1><?= Yii::p('cii', 'Administration dashboard'); ?></h1>
    <p class="lead"><?= Yii::p('cii', 'Welcome to the administration panel from cii'); ?></p>
    
    
    <div class="body-content">
        <?php 

        $items = [
            [
                'encode' => false,
                'label' => '<i class="glyphicon glyphicon-question-sign"></i> ' . Yii::p('cii', 'Information'),
                'content' => $this->render('_welcome')
            ]
        ];

        if(Yii::$app->user->can(['cii', [Permission::MANAGE_USER, Permission::MANAGE_ADMIN]])) {
            $items[] = [
                'encode' => false,
                'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::p('cii', 'User'),
                'content' => $this->render('_user')
            ];
        }
        
        if(Yii::$app->user->can(['cii', [Permission::MANAGE_ROUTE, Permission::MANAGE_ADMIN]])) {
            $items[] = [
                'encode' => false,
                'label' => '<i class="glyphicon glyphicon-link"></i> ' . Yii::p('cii', 'Route'),
                'content' => $this->render('_route')
            ];
        }

        echo Tabs::widget([
            'id' => 'backend-dashboard',
            'items' => $items
        ]); ?>
        

    </div>
</div>
