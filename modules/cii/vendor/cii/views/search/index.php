<?php
use yii\widgets\ActiveForm;
use cii\helpers\Html;
?>
<div class="well">
    <?php $form = ActiveForm::begin([
        'method' => 'get'
    ]); 

    $count = 0;
    foreach($attributes as $name => $formatter) {
        if($count % 2 == 0) {
            echo '<div class="row">';
        }

        echo '<div class="col-md-6">',
            $this->render($formatter[0], [
                'model' => $model,
                'form' => $form,
                'name' => $name
            ]),
            '</div>'
        ;

        if($count % 2 != 0) {
            echo '</div>';
        }

        $count++;
    }

    if($count % 2 != 0) {
        echo '</div>';
    }

    ?>

    <hr>

    <div class="form-group text-center">
        <?= Html::submitButton(Yii::t('yii', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::submitButton(Yii::t('yii', 'Reset'), ['class' => 'btn btn-default', 'data-controller' => 'reset-form']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>