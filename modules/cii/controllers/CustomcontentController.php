<?php

namespace app\modules\cii\controllers;

use Yii;
use cii\web\Controller;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use cii\helpers\Url;


use cii\helpers\SPL;
use cii\web\SecurityException;



use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;

class CustomcontentController extends Controller {
    public function getAccessRoles() {
        return [Permission::MANAGE_CONTENT, Permission::MANAGE_ADMIN];
    }

    public function actionIndex() {
        //this should only happen if there is no startpage
        return $this->render('customcontent/index');
    }


    public function isVisibleInShadow($content) {
        if($content instanceof UserLoginContent) {
            return Yii::$app->user->isGuest;
        }else if($content instanceof UserLogoutContent) {
            return !Yii::$app->user->isGuest;
        }

        return true;
    }

    public function customShadow($content, $position) {
        $model = new LoginForm();
        $model->setContentFormName($content, $position);

        $model = $this->processLogin($content, $model);

        return $this->renderShadow('login_shadow', [
            'model' => $model,
            'content' => $content,
            'position' => $position
        ]);
    }

    public function getLazyLabel() {
        return '<i class="glyphicon glyphicon-modal-window"></i> Costum Content';
    }


    public function getLazyCreate($model = null, $form = null) {
        return $this->renderAjax('_form', [
            'form' => $form ?: ActiveForm::begin()
        ]);
    }

    public function getLazyUpdate($model, $form) {
        return $this->renderAjax('_form', [
            'model' => $model,
            'routes' => Yii::$app->cii->route->getRoutesForDropdown(),
            'form' => $form
        ]);
    }

    public function getLazyView($model) {
        return $this->renderAjax('_view', [
            'model' => $model
        ]);
    }
}
