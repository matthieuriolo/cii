<?php

use yii\helpers\Html;

?>

<div class="row">
	<div class="col-md-6">
		<?= $form->field($model, 'content_id')->dropDownList($contents); ?>
	</div>
</div>