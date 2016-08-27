<?php
use yii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = Yii::p('cii', 'Profile');
$this->params['breadcrumbs'][] = $this->title;



$items = [];

$items[] = [
	'encode' => false,
	'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::p('cii', 'User'),
	'content' => $this->render('_user', [
        'model' => $model
    ])
];

if($content->show_groups) {
	$items[] = [
		'encode' => false,
		'label' => '<i class="glyphicon glyphicon-tags"></i> ' . Yii::p('cii', 'Group'),
		'content' => $this->render('_group', [
	        'data' => $groups
	    ])
	];
}

?>
<main>
	<?php echo Html::a(
        Yii::p('cii', 'Edit profile'),
        [\Yii::$app->seo->relativeRoute('app\modules\cii\routes\profile', 'edit')],
        ['class' => 'btn btn-success pull-right']
    ); ?>

	<h1><?php echo Yii::p('cii', 'Profile'); ?></h1>

	<p class="lead"><?php echo Yii::p('cii', 'Overview of the user {user}', ['user' => $model->username]); ?></p>

	<?= Tabs::widget([
            'items' => $items
        ]);
    ?>
</main>