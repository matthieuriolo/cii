<?php

use cii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = 'Authentication';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?php echo Html::encode($this->title); ?></h1>
<p class="lead"><?php echo Html::encode(Yii::p('cii', 'Graphs and other statistical summaries')); ?></p>

<?php echo $this->render('_auth_information'); ?>

