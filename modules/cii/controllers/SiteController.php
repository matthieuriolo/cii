<?php

namespace app\modules\cii\controllers;

use Yii;
use cii\web\Controller;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use cii\helpers\Url;

use cii\web\routes\StartpageRoute;
use cii\helpers\SPL;
use cii\web\SecurityException;

use app\modules\cii\models\common\Route;
use app\modules\cii\models\route\ContentRoute;
use app\modules\cii\models\route\CaptchaRoute;
use app\modules\cii\models\auth\ForgotForm;
use app\modules\cii\models\auth\UserLoginForm;
use app\modules\cii\models\auth\LogoutForm;
use app\modules\cii\models\auth\RegisterForm;
use app\modules\cii\models\auth\User;
use app\modules\cii\models\auth\GroupMember;


use app\modules\cii\models\content\UserLoginContent;
use app\modules\cii\models\content\UserLogoutContent;


class SiteController extends Controller {
    public function actions() {
        $captchaAction = [
            'class' => 'cii\captcha\CaptchaAction',
        ];

        if(Yii::$app->seo) {
            $captchaAction['url'] = '//' . Yii::$app->seo->getBaseRoute();
            $route = Yii::$app->seo->getCalledModelRoute();

            if($route instanceof CaptchaRoute) {
                if($route->length_min) {
                    $captchaAction['minLength'] = $route->length_min;
                }

                if($route->length_max) {
                    $captchaAction['maxLength'] = $route->length_max;
                }

                if($route->width) {
                    $captchaAction['width'] = $route->width;
                }

                if($route->height) {
                    $captchaAction['height'] = $route->height;
                }

                if($route->font_color) {
                    $captchaAction['foreColor'] = hexdec(substr($route->font_color, 1));
                }

                if($route->limit) {
                    $captchaAction['testLimit'] = $route->limit;
                }
            }
        }

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],

            'captcha' => $captchaAction,

            'doc'=>[
                'class'=>'yii\web\ViewAction',
                'viewPrefix' => 'doc'
            ],
        ];
    }

    public function actionIndex() {
        if($model = Yii::$app->cii->package->setting('cii', 'startroute')) {
            $model = $model->outbox();
            Yii::$app->seo = Yii::createObject([
                'class' => 'cii\web\routes\StartpageRoute',
                'loadedModel' => $model,
                'route_id' => $model->route_id,
            ]);
            return $model->forwardToController($this);
        }

        //this should only happen if there is no startpage
        return $this->render('index');
    }

    protected function redirectByContent($content) {
        if($content->redirect_id) {
            $this->redirect(['//' . $content->redirect->getBreadcrumbs()]);
        }else {
            $this->goHome();
        }
    }

    public function actionLogin() {
        $content = Yii::$app->seo->getModel()->content->outbox();
        if(!Yii::$app->user->isGuest) {
            $this->redirectByContent($content);
        }

        $model = $this->processLogin($content);

        return $this->render('login', [
            'model' => $model,
            'content' => $content
        ]);
    }

    protected function processLogin($content, $model = null) {
        if(!$model) {
            $model = new UserLoginForm();
        }

        if($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('success', 'You have been logged in successfully');
            $this->redirectByContent($content);
        }

        return $model;
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        Yii::$app->session->setFlash('success', Yii::p('cii', 'You have been logged out successfully'));
        $content = Yii::$app->seo->getModel()->content->outbox();
        $this->redirectByContent($content);
        return;
    }

    public function actionRegister() {
        $content = Yii::$app->seo->getModel()->content->outbox();
        if(!Yii::$app->user->isGuest) {
            $this->redirectByContent($content);
            return;
        }

        $model = new RegisterForm();
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('warning', 'You have to activate the newly created user');
            $this->redirectByContent($content);
            return;
        }

        return $this->render('register', [
            'model' => $model,
            'content' => $content
        ]);
    }


    public function actionForgot($token = null, $key = null) {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $content = Yii::$app->seo->getModel()->content->outbox();
            $model = new ForgotForm();

            if($model->load(Yii::$app->request->post()) && $model->forgot()) {
                Yii::$app->session->setFlash('warning', 'We sent you a recovery email');
                $model = new ForgotForm();
            }else if(!empty($token) && !empty($key)) {
                $user = User::findOne(['token' => $token]);
                if($user && $user->validateAuthKey($key) && $user->recoverPassword()) {
                    Yii::$app->session->setFlash('success', 'Your password have been reset and sent per mail');
                    $this->redirectByContent($content);
                    $transaction->commit();
                    return;
                }

                throw new SecurityException();
            }

            $transaction->commit();
            return $this->render('forgot', [
                'model' => $model,
                'content' => $content
            ]);
        }catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function actionActivate($token, $key) {
        $content = Yii::$app->seo->getModel()->content->outbox();

        $user = User::findOne(['token' => $token]);
        if($user && $user->validateAuthKey($key)) {
            $user->token = null;
            $user->touch('activated');
            $user->save();

            Yii::$app->session->setFlash('success', 'Your account have been activated successfully');
            return $this->redirectByContent($content);
        }

        throw new SecurityException();
    }

    public function actionContent() {
        $model = null;
        if(Yii::$app->seo) {
            $model = Yii::$app->seo->getModel()->content->outbox();
        }else {
            //we're on the frontpage
            if($route_id = Yii::$app->cii->package->setting('cii', 'startroute')) {
                $model = ContentRoute::findOne(['route_id' => $route_id]);
                if($model) {
                    $model = $model->content->outbox();
                }
            }
        }


        if(SPL::hasInterface($model, 'app\modules\cii\base\ContentInterface')) {
            return $model->forwardToController($this);
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionRedirect() {
        $model = Yii::$app->seo->getModel()->route->outbox();
        $url = URL::home();
        if($model->redirect_id) {
            $url = ['//' . $model->redirect->getBreadcrumbs()];
        }

        $this->redirect($url, $model->type);
    }

    public function isVisibleInShadow($content) {
        if($content instanceof UserLoginContent) {
            return Yii::$app->user->isGuest;
        }else if($content instanceof UserLogoutContent) {
            return !Yii::$app->user->isGuest;
        }

        return true;
    }

    public function loginShadow($content, $position) {
        $model = new UserLoginForm();
        $model->setContentFormName($content, $position);
        $model->captchaRoute = $content->captcha;
        $model = $this->processLogin($content, $model);
        return $this->renderShadow('login_shadow', [
            'model' => $model,
            'content' => $content,
            'position' => $position
        ]);
    }

    public function logoutShadow($content, $position) {
        if(Yii::$app->user->isGuest) {
            $this->redirectByContent($content);
            return;
        }

        $model = new LogoutForm();
        $model->setContentFormName($content, $position);

        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->user->logout();
            Yii::$app->session->setFlash('success', 'You have been logged out successfully');
            $this->redirectByContent($content);
        }

        return $this->renderShadow('logout_shadow', [
            'model' => $model,
            'content' => $content,
            'position' => $position
        ]);
    }
}
