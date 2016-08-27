<?php

use \cii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = 'Log files';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <?php echo Html::a(
        Yii::p('cii', 'Clear logs'),
        [\Yii::$app->seo->relativeAdminRoute('flushlog')],
        ['class' => 'btn btn-success pull-right']
    ); ?>
    
    <h1><?= Yii::p('cii', 'System logs'); ?></h1>

    <p class="lead"><?= Yii::p('cii', 'Display all available logs'); ?></p>

    <?php
    echo Tabs::widget([
        'items' => array_map(function($ct) {
                return [
                    'content' => '<pre class="well">' . Html::encode($ct['content']) . '</pre>',
                    'label' => $ct['name']
                ];
            }, $logs),
        ]);
    ?>
</div>
