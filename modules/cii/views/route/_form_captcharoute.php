<?php

use cii\widgets\EditView;

echo EditView::widget([
	'model' => $model,
	'form' => $form,
	'attributes' => [
		'length_min:integer',
		'length_max:integer',
		'width:integer',
		'height:integer',
		'limit:integer',
		'font_color:color',
	],
]);