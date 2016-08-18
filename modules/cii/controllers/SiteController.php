<?php

namespace app\modules\cii\controllers;

use Yii;
use yii\web\Controller;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\web\NotFoundHttpException;
use cii\helpers\SPL;

use cii\web\SecurityException;

use yii\data\ActiveDataProvider;

use app\modules\cii\models\ForgotForm;
use app\modules\cii\models\LoginForm;
use app\modules\cii\models\RegisterForm;
use app\modules\cii\models\User;
use app\modules\cii\models\GroupMember;



class SiteController extends Controller {
    public $layout = '@app/modules/cii/views/layouts/main';

    public function actions() {
        $url = null;
        if(Yii::$app->seo) {
            $url = '//' . Yii::$app->seo->getBaseRoute();
        }
        
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],

            'captcha' => [
                'class' => 'cii\captcha\CaptchaAction',
                'url' => $url
            ],

            'doc'=>[
                'class'=>'yii\web\ViewAction',
                'viewPrefix' => 'doc'
            ],
        ];
    }

    public function actionIndex() {
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
            return;
        }

        
        $model = new LoginForm();
        if($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('success', 'You have been logged in successfully');
            $this->redirectByContent($content);
            return;
        }

        return $this->render('login', [
            'model' => $model,
            'content' => $content
        ]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        Yii::$app->session->setFlash('success', 'You have been logged out successfully');
        $content = Yii::$app->seo->getModel()->content->outbox();
        $this->redirectByContent($content);
        return;
    }

    public function actionRegister() {
        $content = Yii::$app->seo->getModel()->content->outbox();

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
        $model = Yii::$app->seo->getModel()->content->outbox();
        if(SPL::hasInterface($model, 'app\modules\cii\base\ContentInterface')) {
            return $model->forwardToController($this);
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

/*
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'error',
                            'index',
                            'login',
                            'content',
                            'captcha',
                        ],

                        'allow' => true,
                    ],

                    [
                        'actions' => [
                            'login',
                            'register',
                            'forgot',
                            'activate',
                        ],

                        'allow' => true,
                        'roles' => ['?'],
                    ],

                    [
                        'actions' => [
                            'content',
                            'logout',
                            'profile'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*
        ];
    }
    */
}
