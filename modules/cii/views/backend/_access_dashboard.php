<?php

use app\modules\cii\widgets\TabbedPanel;
use app\modules\cii\widgets\LineFlot;
use app\modules\cii\widgets\PieFlot;
use app\modules\cii\models\common\Route;

use cii\helpers\Plotter;
use cii\helpers\Html;

use app\modules\cii\Permission;
?>
<div class="form-group text-right">
<?php
if(Yii::$app->getUser()->can(['cii', Permission::MANAGE_ROUTE])) {
    echo Html::a(Yii::p('cii', 'Routes'), [Yii::$app->seo->relativeAdminRoute('route/index')], ['class' => 'btn btn-sm btn-success']);
}
?>
</div>
<?php echo $this->render('_access_information'); ?>