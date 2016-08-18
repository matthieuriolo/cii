<?php

use yii\helpers\Html;

?>


<?= $this->render('_form_contentroute', [
	'model' => $model,
	'form' => $form,
	'contents' => $contents,
]);?>