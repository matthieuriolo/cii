<?php

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1>Administration!</h1>
    <p class="lead">Welcome to the administration panel from cii</p>
    
    <hr>

    <div class="row">
        <div class="col-md-6">Cii version: <?= Yii::$app->getModule('cii')->getVersion(); ?></div>
        <div class="col-md-6">Yii version: <?= Yii::getVersion(); ?></div>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <h2>Users & Group</h2>

                <p>Every access of the webpage is controlled by the RBAC</p>

                <p><a class="btn btn-default" href="<?php echo Yii::$app->urlManager->createUrl([Yii::$app->seo->relativeAdminRoute('modules/cii/user/index')]); ?>">User dashboard &raquo;</a></p>
            </div>

            <div class="col-lg-6">
                <h2>Routes</h2>

                <p>Define how the URL looks like</p>
                    
                <p><a class="btn btn-default" href="<?php echo Yii::$app->urlManager->createUrl([Yii::$app->seo->relativeAdminRoute('modules/cii/route/index')]); ?>">Routes &raquo;</a></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <h2>Contents</h2>

                <p>All visible elements are grouped as contents. New packages usually extend the existing content.</p>

                <p><a class="btn btn-default" href="<?php echo Yii::$app->urlManager->createUrl([Yii::$app->seo->relativeAdminRoute('modules/cii/content/index')]); ?>">Contents &raquo;</a></p>
            </div>

            <div class="col-lg-6">
                <h2>Extensions</h2>

                <p>Cii can be extended with new functionalities. You can install the extensions by uploading a zip file.</p>

                <p><a class="btn btn-default" href="<?php echo Yii::$app->urlManager->createUrl([Yii::$app->seo->relativeAdminRoute('modules/cii/extension/index')]); ?>">Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
