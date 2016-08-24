<?php
use yii\bootstrap\Tabs;
use cii\helpers\Html;

$reflection = $model->getReflection();

$this->title = $modelType . ' - ' . $reflection->getName();

$this->params['breadcrumbs'][] = [
    'label' => $modelType,
    'url' => $modelUrl
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-index">
    <h1><?php echo $modelType; ?> - <?php echo $reflection->getName(); ?></h1>
    <p class="lead"><?php echo $reflection->getDescription(); ?></p>

    <div class="body-content">

        <?php
        echo Tabs::widget([
            'items' => [
                [
                    'encode' => false,
                    'label' => '<i class="glyphicon glyphicon-question-sign"></i> Information',
                    'content' => $this->render('_info', ['model' => $model]),
                    //'active' => true
                ],

                [
                    'encode' => false,
                    'label' => '<i class="glyphicon glyphicon-cog"></i> Settings',
                    'content' => $this->render('/setting/_list', ['data' => $settings]),
                    'headerOptions' => [
                        'class' => count($settings->allModels) ? '' : 'disabled'
                    ]
                ],
            ]]);
        ?>
    </div>
</div>
