<?php

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1><?= Yii::p('cii', 'Administration dashboard'); ?></h1>
    <p class="lead"><?= Yii::p('cii', 'Welcome to the administration panel from cii'); ?></p>
    
    <hr>

    <div class="row">
        <div class="col-md-6"><?= Yii::p('cii', 'Cii version: {version}', ['version' => Yii::$app->getModule('cii')->getVersion()]); ?></div>
        <div class="col-md-6"><?= Yii::p('cii', 'Yii version: {version}', ['version' => Yii::getVersion()]); ?></div>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <h2><?= Yii::p('cii', 'Users & Group'); ?></h2>

                <p><?= Yii::p('cii', 'Every access of the webpage is controlled by the RBAC'); ?></p>

                <p><a class="btn btn-default" href="<?php echo Yii::$app->urlManager->createUrl([Yii::$app->seo->relativeAdminRoute('modules/cii/user/index')]); ?>"><?= Yii::p('cii', 'User dashboard'); ?> &raquo;</a></p>
            </div>

            <div class="col-lg-6">
                <h2><?= Yii::p('cii', 'Routes'); ?></h2>

                <p><?= Yii::p('cii', 'Define how the URL looks like'); ?></p>
                    
                <p><a class="btn btn-default" href="<?php echo Yii::$app->urlManager->createUrl([Yii::$app->seo->relativeAdminRoute('modules/cii/route/index')]); ?>"><?= Yii::p('cii', 'Routes'); ?> &raquo;</a></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <h2><?= Yii::p('cii', 'Contents'); ?></h2>

                <p><?= Yii::p('cii', 'All visible elements are grouped as contents. New packages usually extend the existing content'); ?></p>

                <p><a class="btn btn-default" href="<?php echo Yii::$app->urlManager->createUrl([Yii::$app->seo->relativeAdminRoute('modules/cii/content/index')]); ?>"><?= Yii::p('cii', 'Contents'); ?> &raquo;</a></p>
            </div>

            <div class="col-lg-6">
                <h2><?= Yii::p('cii', 'Extensions'); ?></h2>

                <p><?= Yii::p('cii', 'Cii can be extended with new functionalities. You can install the extensions by uploading a zip file'); ?></p>

                <p><a class="btn btn-default" href="<?php echo Yii::$app->urlManager->createUrl([Yii::$app->seo->relativeAdminRoute('modules/cii/extension/index')]); ?>"><?= Yii::p('cii', 'Extensions'); ?> &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
