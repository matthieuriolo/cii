<?php
use cii\helpers\Html;
use cii\widgets\DetailView;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'slug',
        'enabled:boolean',
        [
        	'attribute' => 'classname',
            'value' => $model->classname->typename
        ],

        'title',
        'created:datetime',
        
        [
        	'attribute' => 'language_id',
        	'format' => 'html',
        	'value' => empty($model->language_id) ? '-' : Html::a($model->language->name, [Yii::$app->seo->relativeAdminRoute('modules/cii/language/view'), 'id' => $model->language->id]),
            'visible' => Yii::$app->cii->package->setting('cii', 'multilanguage')
        ],

        [
        	'attribute' => 'breadcrumb',
        	'format' => 'html',
            'value' => Html::a(
                $model->getBreadcrumbs(),
                ['//'.$model->getBreadcrumbs()],
                [
                    'class' => 'sdasd asd ',
                    'target' => '_blank',
                    'data-pjax' => false,   
                ]
            )
        ]
    ],
]);?>
<hr>
<?php
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'hits',
        'averageHits',
        'dailyHits',
        'weeklyHits',
        'monthlyHits',
        'yearlyHits',
    ],
]);
?>