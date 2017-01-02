<?php
use app\modules\cii\Permission;
use cii\Helpers\Html;

use cii\widgets\RowColumnView;
?>

<?php
$user = Yii::$app->getUser()->getIdentity();
?>
<div class="row">
    <div class="col-md-6"><?= Yii::p('cii', 'Username: {username}', [
            'username' => 
                Yii::$app->getUser()->can(['cii', Permission::MANAGE_USER])
                ? Html::a($user->username, [Yii::$app->seo->relativeAdminRoute('modules/cii/user/view'), 'id' => $user->id])
                : Yii::$app->formatter->asText($user->username)
        ]); ?>
    </div>
    <div class="col-md-6"><?= Yii::p('cii', 'Email: {email}', [
            'email' => Yii::$app->formatter->asEmail($user->email)
        ]); ?>
    </div>
</div>

<hr>

<?php if(Yii::$app->getUser()->can(['cii', Permission::MANAGE_ADMIN])) { ?>
    <div class="row">
        <div class="col-md-6"><?= Yii::p('cii', 'PHP version: {version}', ['version' => phpversion()]); ?></div>
        <div class="col-md-6"><?= Yii::p('cii', 'Cii version: {version}', ['version' => Yii::$app->getModule('cii')->getVersion()]); ?></div>
    </div>

    <div class="row">
        <div class="col-md-6"><?= Yii::p('cii', 'Yii version: {version}', ['version' => Yii::getVersion()]); ?></div>
        <div class="col-md-6"><?= Yii::p('cii', 'Yii debug: {bool}', ['bool' => Yii::$app->formatter->asBoolean(defined('YII_DEBUG') && YII_DEBUG)]); ?></div>
    </div>

    <div class="row">
        <div class="col-md-6"><?= Yii::p('cii', 'GD version: {version}', [
            'version' =>
                function_exists('gd_info') && ($info = gd_info())
                ? $info['GD Version'] 
                : Yii::$app->formatter->asText(null)
            ]); ?>
        </div>

        <div class="col-md-6"><?= Yii::p('cii', 'Imagick version: {version}', [
            'version' => 
                class_exists('Imagick') 
                ? Imagick::getVersion() 
                : Yii::$app->formatter->asText(null)
        ]); ?>
        </div>
    </div>
    
    <hr>
<?php } ?>

<?php 

echo RowColumnView::widget([
    'items' => [
        [
            'content' => 
                '<h2>' . Yii::p('cii', 'Documentation') . '</h2>
                <p>' . Yii::p('cii', 'Access the documentation for the backend area') . '</p>
                <p><a class="btn btn-default" href="' . Yii::$app->urlManager->createUrl([Yii::$app->seo->relativeAdminRoute('doc')]) . '">' . Yii::p('cii', 'Documentation') . ' &raquo;</a></p>'
        ],

        [
            'visible' => Yii::$app->user->can(['cii', Permission::MANAGE_USER]),
            'content' => 
                '<h2>' . Yii::p('cii', 'Users & Group') . '</h2>
                <p>' . Yii::p('cii', 'Every access of the webpage is controlled by the RBAC') . '</p>
                <p><a class="btn btn-default" href="' . Yii::$app->urlManager->createUrl([Yii::$app->seo->relativeAdminRoute('modules/cii/user/index')]) . '">' . Yii::p('cii', 'Users') . ' &raquo;</a></p>'
        ],


        [
            'visible' => Yii::$app->user->can(['cii', Permission::MANAGE_USER]),
            'content' => 
                '<h2>' . Yii::p('cii', 'Routes') . '</h2>
                <p>' . Yii::p('cii', 'Define how the URL looks like') . '</p>
                <p><a class="btn btn-default" href="' . Yii::$app->urlManager->createUrl([Yii::$app->seo->relativeAdminRoute('modules/cii/route/index')]) . '">' . Yii::p('cii', 'Routes') . ' &raquo;</a></p>'
        ],


        [
            'visible' => Yii::$app->user->can(['cii', Permission::MANAGE_CONTENT]),
            'content' => 
                '<h2>' . Yii::p('cii', 'Contents') . '</h2>
                <p>' . Yii::p('cii', 'All visible elements are grouped as contents. New packages usually extend the existing content') . '</p>
                <p><a class="btn btn-default" href="' . Yii::$app->urlManager->createUrl([Yii::$app->seo->relativeAdminRoute('modules/cii/content/index')]) . '">' . Yii::p('cii', 'Contents') . ' &raquo;</a></p>'
        ],

        [
            'visible' => Yii::$app->user->can(['cii', Permission::MANAGE_EXTENSION]),
            'content' => 
                '<h2>' . Yii::p('cii', 'Extensions') . '</h2>
                <p>' . Yii::p('cii', 'Cii can be extended with new functionalities. You can install the extensions by uploading a zip file') . '</p>
                <p><a class="btn btn-default" href="' . Yii::$app->urlManager->createUrl([Yii::$app->seo->relativeAdminRoute('modules/cii/extension/index')]) . '">' . Yii::p('cii', 'Extensions') . ' &raquo;</a></p>'
        ],
    ]
]);
?>