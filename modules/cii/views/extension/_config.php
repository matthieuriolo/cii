<?php 

use yii\grid\GridView;
use yii\grid\ActionColumn;
use cii\helpers\Html;
?>
<br>

<?php echo GridView::widget([
	'dataProvider' => $data,
	'columns' => [
	    'name',
	    'value'
	],
]) ?>
