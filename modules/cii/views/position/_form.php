<?php
use cii\widgets\EditView;
use yii\widgets\ActiveForm;
?>

<?= EditView::widget([
    'columns' => isset($columns) ? $columns : null,
    'model' => $model,
    'form' => $form,
    'attributes' => [
        'content_id:content',
        'position:positionTypes',
        [
            'attribute' => 'route_id',
            'format' => 'route',
            'allowChildren' => false,
        ],
    ],
]) ?>


