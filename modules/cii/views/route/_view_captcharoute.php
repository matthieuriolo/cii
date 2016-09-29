<?php

use cii\widgets\DetailView;

echo DetailView::widget([
	'model' => $model,
	'attributes' => [
		'length_min:integer',
		'length_max:integer',
		'width:integer',
		'height:integer',
		'limit:integer',
		'font_color:color',
	],
]);