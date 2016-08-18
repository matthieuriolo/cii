<?php

namespace app\modules\cii\controllers;

use Yii;
use app\modules\cii\models\User;
use app\modules\cii\models\UserSearch;
use app\modules\cii\models\Group;
use app\modules\cii\models\GroupMember;


use app\modules\cii\models\UserCreateForm;
use app\modules\cii\models\UserUpdateForm;

use app\modules\cii\models\UserMailForm;

use app\modules\cii\models\UserLoginContent;
use app\modules\cii\models\UserLogoutContent;
use app\modules\cii\models\UserRegisterContent;
use app\modules\cii\models\UserActivateContent;
use app\modules\cii\models\UserForgotContent;
use app\modules\cii\models\ProfileRoute;


use app\modules\cii\models\Route;
use app\modules\cii\models\ContentRoute;

use cii\backend\BackendController;
use cii\web\SecurityException;


use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


use app\modules\cii\Permission;


/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return parent::behaviors() + [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function getAccessRoles() {
        return [Permission::MANAGE_USER];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $query = GroupMember::find();

        $query->joinWith(['group as group']);
        $query->where([
            'user_id' => $id
        ]);
        
        $groups = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'created',
                    'group.name'
                ],
            ],
        ]);

        $availableGroups = [null => Yii::t('app', 'No selection')];
        
        $availableGroups += ArrayHelper::map(Group::find()->where(['enabled' => true])->all(), 'id', 'name');
        
        $groupModel = new GroupMember();
        $groupModel->user_id = $id;
        if($groupModel->load(Yii::$app->request->post()) && $groupModel->save()) {
            $groupModel->group_id = null;
        }



        $groupOptions = [];
        foreach(GroupMember::find()->where(['user_id' => $id])->all() as $group) {
            $groupOptions[$group->group_id] = ['disabled' => true];
        }


        return $this->render('view', [
            'model' => $this->findModel($id),
            'groups' => $groups,

            'groupModel' => $groupModel,
            'groupOptions' => $groupOptions,
            'availableGroups' => $availableGroups,
        ]);
    }


    public function actionDeletemember($id) {
        $model = GroupMember::findOne($id);
        $model->delete();
        $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/user/view'), 'id' => $model->user_id]);
        return;
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new UserCreateForm();
        
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/user/view'), 'id' => $model->id]);
            return;
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = UserUpdateForm::findOne(['id' => $id]);

        if(!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/user/view'), 'id' => $model->id]);
            return;
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        if($model->superadmin) {
            throw new SecurityException();
        }

        $model->delete();
        $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/user/index')]);
        return;
    }


    public function actionSwitch($id) {
        $model = $this->findModel($id);
        if($model->superadmin) {
            throw new SecurityException();
        }

        Yii::$app->user->login($model, 0);
        Yii::$app->session->setFlash('success', 'Successfully changed user');
        $this->goHome();
    }

    public function actionMail($id) {
        if(!($model = UserMailForm::findOne($id))) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if($model->load(Yii::$app->request->post()) && $model->send()) {
            Yii::$app->session->setFlash('success', 'Successfully send mail');
            $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/user/view'), 'id' => $id]);
            return;
        }

        return $this->render('mail', [
            'model' => $model
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }







    protected function fetchRoutes($type, $allowEmpty = true) {
        $models = ContentRoute::find()
            ->joinWith([
                'content as content',
                'route as route',
                'content.classname as classname',
            ])
            ->where([
                'route.enabled' => true,
                'content.enabled' => true,
                'classname.path' => $type
            ])->all();

        $ret = [];
        if($allowEmpty) {
            $ret[null] = Yii::t('app', 'No selection');
        }

        return $ret + ArrayHelper::map($models, 'route.id', 'route.slug');
    }



    public function getLazyLoginLabel() {
        return '<i class="glyphicon glyphicon-user"></i> Login';
    }

    public function getLazyLoginCreate($model = null, $form = null) {
        if(!$model) {
            $model = new UserLoginContent();
        }

        return $this->getLazyLoginForm($model, $form ?: ActiveForm::begin());
    }

    public function getLazyLoginUpdate($model, $form) {
        return $this->getLazyLoginForm($model, $form);
    }

    protected function getLazyLoginForm($model, $form) {
        return $this->renderAjax('_form_userlogincontent', [
            'model' => $model,
            'form' => $form,
            'routes' => Yii::$app->cii->route->getRoutesForDropdown(),
            'routesRegister' => $this->fetchRoutes('app\modules\cii\models\UserRegisterContent'),
            'routesForgot' => $this->fetchRoutes('app\modules\cii\models\UserForgotContent')
        ]);
    }

    public function getLazyLoginView($model) {
        return $this->renderAjax('_view_userlogincontent', [
            'model' => $model,
        ]);
    }



    public function getLazyLogoutLabel() {
        return '<i class="glyphicon glyphicon-user"></i> Logout';
    }

    public function getLazyLogoutCreate($model = null, $form = null) {
        if(!$model) {
            $model = new UserLogoutContent();
        }

        return $this->getLazyLogoutForm($model, $form ?: ActiveForm::begin());
    }

    public function getLazyLogoutUpdate($model, $form) {
        return $this->getLazyLogoutForm($model, $form);
    }

    protected function getLazyLogoutForm($model, $form) {
        return $this->renderAjax('_form_userlogoutcontent', [
            'model' => $model,
            'form' => $form,
            'routes' => Yii::$app->cii->route->getRoutesForDropdown()
        ]);
    }

    public function getLazyLogoutView($model) {
        return $this->renderAjax('_view_userlogoutcontent', [
            'model' => $model,
        ]);
    }



    public function getLazyForgotLabel() {
        return '<i class="glyphicon glyphicon-user"></i> Forgot';
    }

    public function getLazyForgotCreate($model = null, $form = null) {
        if(!$model) {
            $model = new UserForgotContent();
        }

        return $this->getLazyForgotForm($model, $form ?: ActiveForm::begin());
    }

    public function getLazyForgotUpdate($model, $form) {
        return $this->getLazyForgotForm($model, $form);
    }

    protected function getLazyForgotForm($model, $form) {
        return $this->renderAjax('_form_userforgotcontent', [
            'model' => $model,
            'form' => $form,
            'routes' => Yii::$app->cii->route->getRoutesForDropdown(),
            'routesRegister' => $this->fetchRoutes('app\modules\cii\models\UserRegisterContent'),
            'routesLogin' => $this->fetchRoutes('app\modules\cii\models\UserLoginContent'),
        ]);
    }

    public function getLazyForgotView($model) {
        return $this->renderAjax('_view_userforgotcontent', [
            'model' => $model,
        ]);
    }





    public function getLazyActivateLabel() {
        return '<i class="glyphicon glyphicon-user"></i> Activate';
    }

    public function getLazyActivateCreate($model = null, $form = null) {
        if(!$model) {
            $model = new UserActivateContent();
        }

        return $this->getLazyActivateForm($model, $form ?: ActiveForm::begin());
    }

    public function getLazyActivateUpdate($model, $form) {
        return $this->getLazyActivateForm($model, $form);
    }

    protected function getLazyActivateForm($model, $form) {
        return $this->renderAjax('_form_useractivatecontent', [
            'model' => $model,
            'form' => $form,
            'routes' => Yii::$app->cii->route->getRoutesForDropdown(),
            'routesRegister' => $this->fetchRoutes('app\modules\cii\models\UserRegisterContent'),
            'routesLogin' => $this->fetchRoutes('app\modules\cii\models\UserLoginContent'),
        ]);
    }

    public function getLazyActivateView($model) {
        return $this->renderAjax('_view_useractivatecontent', [
            'model' => $model,
        ]);
    }




    public function getLazyRegisterLabel() {
        return '<i class="glyphicon glyphicon-user"></i> Register';
    }

    public function getLazyRegisterCreate($model = null, $form = null) {
        if(!$model) {
            $model = new UserRegisterContent();
        }

        return $this->getLazyRegisterForm($model, $form ?: ActiveForm::begin());
    }

    public function getLazyRegisterUpdate($model, $form) {
        return $this->getLazyRegisterForm($model, $form);
    }

    protected function getLazyRegisterForm($model, $form) {
        return $this->renderAjax('_form_userregistercontent', [
            'model' => $model,
            'form' => $form,
            'routes' => Yii::$app->cii->route->getRoutesForDropdown(),
            'routesRegister' => $this->fetchRoutes('app\modules\cii\models\UserRegisterContent'),
            'routesLogin' => $this->fetchRoutes('app\modules\cii\models\UserLoginContent'),
            'routesForgot' => $this->fetchRoutes('app\modules\cii\models\UserForgotContent'),
            'routesActivate' => $this->fetchRoutes('app\modules\cii\models\UserActivateContent'),
        ]);
    }

    public function getLazyRegisterView($model) {
        return $this->renderAjax('_view_userregistercontent', [
            'model' => $model,
        ]);
    }










    public function getLazyProfileLabel() {
        return '<i class="glyphicon glyphicon-user"></i> Profile';
    }

    public function getLazyProfileCreate($model = null, $form = null) {
        if(!$model) {
            $model = new ProfileRoute();
        }

        return $this->getLazyProfileForm($model, $form ?: ActiveForm::begin());
    }

    public function getLazyProfileUpdate($model, $form) {
        return $this->getLazyProfileForm($model, $form);
    }

    protected function getLazyProfileForm($model, $form) {
        return $this->renderAjax('_form_userprofilecontent', [
            'model' => $model,
            'form' => $form
        ]);
    }

    public function getLazyProfileView($model) {
        return $this->renderAjax('_view_userprofilecontent', [
            'model' => $model,
        ]);
    }
}
