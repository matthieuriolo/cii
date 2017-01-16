<?php

use app\modules\cii\widgets\TabbedPanel;
use app\modules\cii\widgets\LineFlot;
use app\modules\cii\widgets\PieFlot;
use app\modules\cii\models\auth\User;
use app\modules\cii\models\auth\Group;

use cii\helpers\Plotter;
use cii\helpers\Html;

use cii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\modules\cii\Permission;

?>
<div class="form-group text-right">
<?php
if(Yii::$app->getUser()->can(['cii', Permission::MANAGE_GROUP])) {
	echo Html::a(Yii::p('cii', 'Groups'), [Yii::$app->seo->relativeAdminRoute('group/index')], ['class' => 'btn btn-sm btn-success']);
}

if(Yii::$app->getUser()->can(['cii', Permission::MANAGE_USER])) {
	echo '&nbsp;', Html::a(Yii::p('cii', 'Users'), [Yii::$app->seo->relativeAdminRoute('user/index')], ['class' => 'btn btn-sm btn-success']);
}

?>
</div>
<?php echo $this->render('_auth_information'); ?>
