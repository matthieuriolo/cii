<?php
use cii\helpers\Html;
use cii\widgets\DetailView;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'slug',
        'enabled:boolean',
        [
        	'attribute' => 'classname.path',
            'format' => 'routetypes',
        ],

        'title',
        'created:datetime',
        
        [
        	'attribute' => 'language_id',
        	'format' => 'language',
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
        'hits:integer',
        'averageHits:integer',
        'dailyHits:integer',
        'weeklyHits:integer',
        'monthlyHits:integer',
        'yearlyHits:integer',
    ],
]);
?>