<?php

namespace app\modules\cii\controllers;

use Yii;

use app\modules\cii\models\auth\Group;
use app\modules\cii\models\auth\GroupMember;
use app\modules\cii\models\auth\Permission;
use app\modules\cii\models\auth\PermissionForm;

use app\modules\cii\Permission as MPermission;

use cii\base\SearchModel;
use cii\backend\BackendController;

use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * GroupController implements the CRUD actions for Group model.
 */
class GroupController extends BackendController {
    public function getAccessRoles() {
        return [MPermission::MANAGE_GROUP, MPermission::MANAGE_ADMIN];
    }

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

    /**
     * Lists all Group models.
     * @return mixed
     */
    public function actionIndex() {
        $query = Group::find();

        $model = new SearchModel(Group::className());
        $model->stringFilter('name');
        $model->booleanFilter('enabled');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if($model->load(Yii::$app->request->get()) && $model->validate()) {
            $query = $model->applyFilter($query);
        }

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Group model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $users = new ActiveDataProvider([
            'query' => GroupMember::find()->joinWith('user as user'),
            'sort' => [
                'attributes' => [
                    'created',
                    'user.username' => [
                        'asc' => ['user.username' => SORT_ASC],
                        'desc' => ['user.username' => SORT_DESC],
                    ]
                ],
            ],
        ]);


        $permissions = new ActiveDataProvider([
            'query' => Permission::find(),
            /*
            'sort' => [
                'attributes' => [
                    'permission_id'
                ],
            ],
            */
        ]);


        $availablePermissions = Yii::$app->cii->package->getPermissionTypes(true);

        $permissionModel = new PermissionForm();
        if($permissionModel->load(Yii::$app->request->post()) && $permissionModel->validate()) {
            $permissionModel->assign($id);
        }



        $permissionOptions = [];
        foreach(Permission::find()->where(['group_id' => $id])->all() as $perm) {
            $permissionOptions[$perm->package->name . '-' . $perm->permission_id] = ['disabled' => true];
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'users' => $users,

            'permissions' => $permissions,
            'permissionModel' => $permissionModel,
            'availablePermissions' => $availablePermissions,
            'permissionOptions' => $permissionOptions,
        ]);
    }

    /**
     * Creates a new Group model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Group();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/group/view'), 'id' => $model->id]);
            return;
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Group model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/group/view'), 'id' => $model->id]);
            return;
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Group model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/group/index')]);
        return;
    }


    public function actionDeletepermission($id) {
        $permission = Permission::findOne($id);
        $permission->delete();
        $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/group/view'), 'id' => $permission->group_id]);
        return;
    }

    /**
     * Finds the Group model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Group the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Group::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
