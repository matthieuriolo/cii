<?php

use cii\helpers\Url;
use yii\helpers\Html;
use cii\widgets\DetailView;


$url = Html::a(Url::home(), Url::home());
if($model->redirect_id) {
    $url = Html::a($model->redirect->slug, [Yii::$app->seo->relativeAdminRoute('route/view'), 'id' => $model->redirect_id]);
}else if(!empty($model->url)) {
    $url = Html::a($model->url, $model->url);
}


?>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
        	'attribute' => 'redirect.slug',
        	'format' => 'html',
        	'value' => $url,
        ],

        [
            'attribute' => 'type',
            'value' => $model->getTypes()[$model->type],
        ]
    ],
]) ?>