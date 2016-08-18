<?php
use yii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = Yii::t('app', 'Profile');
$this->params['breadcrumbs'][] = $this->title;





$items = [];

$items[] = [
	'encode' => false,
	'label' => '<i class="glyphicon glyphicon-user"></i> User',
	'content' => $this->render('_user', [
        'model' => $model
    ])
];

if($content->show_groups) {
	$items[] = [
		'encode' => false,
		'label' => '<i class="glyphicon glyphicon-tags"></i> Group',
		'content' => $this->render('_group', [
	        'data' => $groups
	    ])
	];
}

?>
<main>
	<?php echo Html::a(
        Yii::t('yii', 'Edit profile'),
        [\Yii::$app->seo->relativeRoute('app\modules\cii\routes\profile', 'edit')],
        ['class' => 'btn btn-success pull-right']
    ); ?>

	<h1><?php echo Yii::t('app', 'Profile'); ?></h1>

	<p class="lead"><?php echo Yii::t('app', 'Overview of the user {user}', ['user' => $model->username]); ?></p>

	<?= Tabs::widget([
            'items' => $items
        ]);
    ?>
</main>