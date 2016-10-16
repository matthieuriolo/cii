<?php
use yii\helpers\Json;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\layouts\cii\assets\AppAsset;


use cii\widgets\BackendMenu;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <?php if($favicon = Yii::$app->cii->layout->setting('cii', 'favicon')) { ?>
        <link rel="shortcut icon" href="<?php
        echo Yii::$app->request->baseUrl , '/' , $favicon;
        ?>" type="image/x-icon" />
    <?php } ?>

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="body-raw">
<?php $this->beginBody() ?>


<div class="wrap wrap-raw">
    <?php
    foreach(Yii::$app->session->getAllFlashes() as $key => $message) {
        echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
    } ?>

    <?= $content ?>
</div>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
