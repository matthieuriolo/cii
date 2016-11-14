<?php

namespace app\modules\cii\controllers;

use Yii;


use app\modules\cii\models\auth\Group;
use app\modules\cii\models\auth\GroupMember;
use app\modules\cii\models\common\ContentVisibilities;
use app\modules\cii\models\common\Route;
use app\modules\cii\models\route\ContentRoute;

use cii\backend\BackendController;
use cii\web\SecurityException;
use cii\base\SearchModel;

use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


use app\modules\cii\Permission;


/**
 * PositionController implements the CRUD actions for ContentVisibility model.
 */
class PositionController extends PositionBaseController {
    /**
     * Lists all ContentVisibilities models.
     * @return mixed
     */
    public function actionIndex() {
        $query = ContentVisibilities::find();

        $model = new SearchModel(ContentVisibilities::className());
        $model->positionTypesFilter('position');
        $model->contentFilter('content_id');
        $model->routeFilter('route_id');
        
        if(Yii::$app->cii->package->setting('cii', 'multilanguage')) {
            $model->languageFilter('language_id');
        }


        if($model->load(Yii::$app->request->get()) && $model->validate()) {
            $query = $model->applyFilter($query);
        }


        $query->joinWith('content as content');
        $query->joinWith('route as route');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'ordering',
                    'position',
                    'route.slug',
                    'content' => [
                        'asc' => ['content.name' => SORT_ASC],
                        'desc' => ['content.name' => SORT_DESC],
                    ],
                ]
            ] 
        ]);

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContentVisibilities model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing ContentVisibilities model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/position/view'), 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionCreate() {
        $model = new ContentVisibilities();
        
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/position/view'), 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
        return '<i class="glyphicon glyphicon-ContentVisibilities"></i> Login';
    }

    public function getLazyLoginCreate($model = null, $form = null) {
        if(!$model) {
            $model = new ContentVisibilitiesLoginContent();
        }

        return $this->getLazyLoginForm($model, $form ?: ActiveForm::begin());
    }

    public function getLazyLoginUpdate($model, $form) {
        return $this->getLazyLoginForm($model, $form);
    }

    protected function getLazyLoginForm($model, $form) {
        return $this->renderAjax('_form_ContentVisibilitieslogincontent', [
            'model' => $model,
            'form' => $form,
            'routes' => Yii::$app->cii->route->getRoutesForDropdown(),
            'routesRegister' => $this->fetchRoutes('app\modules\cii\models\ContentVisibilitiesRegisterContent'),
            'routesForgot' => $this->fetchRoutes('app\modules\cii\models\ContentVisibilitiesForgotContent')
        ]);
    }

    public function getLazyLoginView($model) {
        return $this->renderAjax('_view_ContentVisibilitieslogincontent', [
            'model' => $model,
        ]);
    }



    public function getLazyLogoutLabel() {
        return '<i class="glyphicon glyphicon-ContentVisibilities"></i> Logout';
    }

    public function getLazyLogoutCreate($model = null, $form = null) {
        if(!$model) {
            $model = new ContentVisibilitiesLogoutContent();
        }

        return $this->getLazyLogoutForm($model, $form ?: ActiveForm::begin());
    }

    public function getLazyLogoutUpdate($model, $form) {
        return $this->getLazyLogoutForm($model, $form);
    }

    protected function getLazyLogoutForm($model, $form) {
        return $this->renderAjax('_form_ContentVisibilitieslogoutcontent', [
            'model' => $model,
            'form' => $form,
            'routes' => Yii::$app->cii->route->getRoutesForDropdown()
        ]);
    }

    public function getLazyLogoutView($model) {
        return $this->renderAjax('_view_ContentVisibilitieslogoutcontent', [
            'model' => $model,
        ]);
    }



    public function getLazyForgotLabel() {
        return '<i class="glyphicon glyphicon-ContentVisibilities"></i> Forgot';
    }

    public function getLazyForgotCreate($model = null, $form = null) {
        if(!$model) {
            $model = new ContentVisibilitiesForgotContent();
        }

        return $this->getLazyForgotForm($model, $form ?: ActiveForm::begin());
    }

    public function getLazyForgotUpdate($model, $form) {
        return $this->getLazyForgotForm($model, $form);
    }

    protected function getLazyForgotForm($model, $form) {
        return $this->renderAjax('_form_ContentVisibilitiesforgotcontent', [
            'model' => $model,
            'form' => $form,
            'routes' => Yii::$app->cii->route->getRoutesForDropdown(),
            'routesRegister' => $this->fetchRoutes('app\modules\cii\models\ContentVisibilitiesRegisterContent'),
            'routesLogin' => $this->fetchRoutes('app\modules\cii\models\ContentVisibilitiesLoginContent'),
        ]);
    }

    public function getLazyForgotView($model) {
        return $this->renderAjax('_view_ContentVisibilitiesforgotcontent', [
            'model' => $model,
        ]);
    }





    public function getLazyActivateLabel() {
        return '<i class="glyphicon glyphicon-ContentVisibilities"></i> Activate';
    }

    public function getLazyActivateCreate($model = null, $form = null) {
        if(!$model) {
            $model = new ContentVisibilitiesActivateContent();
        }

        return $this->getLazyActivateForm($model, $form ?: ActiveForm::begin());
    }

    public function getLazyActivateUpdate($model, $form) {
        return $this->getLazyActivateForm($model, $form);
    }

    protected function getLazyActivateForm($model, $form) {
        return $this->renderAjax('_form_ContentVisibilitiesactivatecontent', [
            'model' => $model,
            'form' => $form,
            'routes' => Yii::$app->cii->route->getRoutesForDropdown(),
            'routesRegister' => $this->fetchRoutes('app\modules\cii\models\ContentVisibilitiesRegisterContent'),
            'routesLogin' => $this->fetchRoutes('app\modules\cii\models\ContentVisibilitiesLoginContent'),
        ]);
    }

    public function getLazyActivateView($model) {
        return $this->renderAjax('_view_ContentVisibilitiesactivatecontent', [
            'model' => $model,
        ]);
    }




    public function getLazyRegisterLabel() {
        return '<i class="glyphicon glyphicon-ContentVisibilities"></i> Register';
    }

    public function getLazyRegisterCreate($model = null, $form = null) {
        if(!$model) {
            $model = new ContentVisibilitiesRegisterContent();
        }

        return $this->getLazyRegisterForm($model, $form ?: ActiveForm::begin());
    }

    public function getLazyRegisterUpdate($model, $form) {
        return $this->getLazyRegisterForm($model, $form);
    }

    protected function getLazyRegisterForm($model, $form) {
        return $this->renderAjax('_form_ContentVisibilitiesregistercontent', [
            'model' => $model,
            'form' => $form,
            'routes' => Yii::$app->cii->route->getRoutesForDropdown(),
            'routesRegister' => $this->fetchRoutes('app\modules\cii\models\ContentVisibilitiesRegisterContent'),
            'routesLogin' => $this->fetchRoutes('app\modules\cii\models\ContentVisibilitiesLoginContent'),
            'routesForgot' => $this->fetchRoutes('app\modules\cii\models\ContentVisibilitiesForgotContent'),
            'routesActivate' => $this->fetchRoutes('app\modules\cii\models\ContentVisibilitiesActivateContent'),
        ]);
    }

    public function getLazyRegisterView($model) {
        return $this->renderAjax('_view_ContentVisibilitiesregistercontent', [
            'model' => $model,
        ]);
    }










    public function getLazyProfileLabel() {
        return '<i class="glyphicon glyphicon-ContentVisibilities"></i> Profile';
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
        return $this->renderAjax('_form_ContentVisibilitiesprofilecontent', [
            'model' => $model,
            'form' => $form
        ]);
    }

    public function getLazyProfileView($model) {
        return $this->renderAjax('_view_ContentVisibilitiesprofilecontent', [
            'model' => $model,
        ]);
    }
}
