<?php
use yii\widgets\Pjax;

Pjax::begin([
	'id' => $pjaxid
]);

echo 'test';

Pjax::end();