<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\widgets\Pjax;

use cii\helpers\SPL;
use cii\widgets\PjaxBreadcrumbs;

$this->title = Yii::p('cii', 'Create Content');
$this->params['breadcrumbs'][] = [
	'label' => Yii::p('cii', 'Contents'),
	'url' => [\Yii::$app->seo->relativeAdminRoute('content/index')]
];
$this->params['breadcrumbs'][] = $this->title;


$pjaxid = Yii::$app->request->pjaxid();
if($pjaxid) {
    echo PjaxBreadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
    ]);
}
?>

<div class="content-create">
	<?php $form = ActiveForm::begin([
        'action' => [
            Yii::$app->seo->relativeAdminRoute('content/create'),
        ],
        'options' => [
            'data-pjax' => (bool)$pjaxid
        ]
    ]); ?>
	<div class="form-group pull-right">
		<?php
		echo Html::a(
            Yii::p('cii', 'Cancel'),
            [
                \Yii::$app->seo->relativeAdminRoute('content/index')
            ],
            ['class' => 'btn btn-warning']
        ),
        '&nbsp;',
        Html::submitButton(Yii::p('cii', 'Create'), ['class' => 'btn btn-success']) ?>
    </div>
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="body-content">
        <?php
        $items = [
            [
                'encode' => false,
                'label' => '<i class="glyphicon glyphicon-file"></i> ' . Yii::p('cii', 'Content'),
                'content' => $this->render('_form', [
                    'model' => $model,
                    'form' => $form,
                    'types' => $types,
                ])
            ],
        ];

        if($topmodel) {
            $info = $topmodel->className();
            if(SPL::hasInterface($info, 'app\modules\cii\base\LazyModelInterface') && $info::hasLazyCRUD()) {
                $info = $info::getLazyCrud();
                $items[] = [
                    'encode' => false,
                    'label' => $info['controller']->$info['label'](),
                    'content' => $info['controller']->$info['create']($topmodel, $form),
                ];
            }
        }

        echo Tabs::widget([
            'id' => uniqid(),
            'items' => $items
        ]);
        ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>
