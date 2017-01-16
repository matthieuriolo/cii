<?php
use app\modules\cii\Permission;
use cii\Helpers\Html;
use \ReflectionExtension;

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
        <div class="col-md-6"><?= Yii::p('cii', 'PHP: {version}', ['version' => phpversion()]); ?></div>
        <div class="col-md-6"><?= Yii::p('cii', 'Cii: {version}', ['version' => Yii::$app->getModule('cii')->getVersion()]); ?></div>
    </div>

    <div class="row">
        <div class="col-md-6"><?= Yii::p('cii', 'Yii: {version}', ['version' => Yii::getVersion()]); ?></div>
        <div class="col-md-6"><?= Yii::p('cii', 'Yii debug: {bool}', ['bool' => Yii::$app->formatter->asBoolean(defined('YII_DEBUG') && YII_DEBUG)]); ?></div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-6"><?= Yii::p('cii', 'GD: {version}', [
            'version' =>
                function_exists('gd_info') && ($info = gd_info())
                ? $info['GD Version'] 
                : Yii::$app->formatter->asText(null)
            ]); ?>
        </div>

        <div class="col-md-6"><?= Yii::p('cii', 'Imagick: {version}', [
            'version' => 
                class_exists('Imagick') 
                ? Imagick::getVersion() 
                : Yii::$app->formatter->asText(null)
        ]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6"><?= Yii::p('cii', 'CURL: {version}', [
            'version' => 
                function_exists('curl_version') 
                ? curl_version()['version'] 
                : Yii::$app->formatter->asText(null)
        ]); ?>
        </div>
        <div class="col-md-6"><?= Yii::p('cii', 'Zip: {version}', [
            'version' =>
                Yii::$app->formatter->asText(phpversion('zip') ?: null)
            ]); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6"><?= Yii::p('cii', 'OpenSSL: {version}', [
            'version' => 
                Yii::$app->formatter->asText(str_replace('OpenSSL ', '', OPENSSL_VERSION_TEXT))
        ]); ?>
        </div>
        <div class="col-md-6"><?= Yii::p('cii', 'Mcrypt: {version}', [
            'version' =>
                Yii::$app->formatter->asText(phpversion('mcrypt') ?: null)
            ]); ?>
        </div> 
    </div>

    <div class="row">
        <div class="col-md-6"><?= Yii::p('cii', 'APC: {version}', [
            'version' => 
                function_exists('apc_cache_info') 
                ? (new ReflectionExtension('apc'))->getVersion() 
                : Yii::$app->formatter->asText(null)
        ]); ?>
        </div>
        <div class="col-md-6"><?= Yii::p('cii', 'Memcache: {version}', [
            'version' =>
                Yii::$app->formatter->asText(phpversion('memcache') ?: null)
            ]); ?>
        </div>
    </div>

    <hr>
<?php } ?>