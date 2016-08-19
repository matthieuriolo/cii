<?php

use yii\helpers\Html;
use cii\widgets\DetailView;

?>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
        	'attribute' => 'content.name',
        	'format' => 'html',
        	'value' => Html::a($model->content->name, [Yii::$app->seo->relativeAdminRoute('modules/cii/content/view'), 'id' => $model->content->id])
        ],

        'keys',
        'description',
        'robots',
        'image',
        'type',
    ],
]) ?>