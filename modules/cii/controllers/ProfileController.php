<?php

namespace app\modules\cii\controllers;

use Yii;
use app\modules\cii\models\auth\User;
use app\modules\cii\models\auth\Group;
use app\modules\cii\models\auth\GroupMember;
use app\modules\cii\models\auth\UpdateForm;
use app\modules\cii\models\auth\PasswordForm;
use app\modules\cii\models\auth\EmailForm;
use app\modules\cii\models\auth\DeleteForm;


use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use yii\web\Controller;

class ProfileController extends Controller {
    public $layout = '@app/modules/cii/views/layouts/main';

    public function actionIndex() {
        $model = Yii::$app->user->getIdentity();
        $content = Yii::$app->seo->getModel();


        $query = GroupMember::find();

        $query->joinWith(['group as group']);
        $query->where([
            'group.enabled' => true,
            'user_id' => $model->id
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


        return $this->render('index', [
            'model' => $model,
            'content' => $content,
            'groups' => $groups,
        ]);
    }


    public function actionEdit() {
        $model = UpdateForm::findOne(Yii::$app->user->getIdentity()->id);
        $content = Yii::$app->seo->getModel();

        if($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Successfully updated');
            $this->redirect(['//' . Yii::$app->seo->getModel()->route->getBreadcrumbs()]);
            return;
        }

        return $this->render('update', [
            'model' => $model,
            'content' => $content
        ]);
    }
    
    public function actionEmail() {
        $model = EmailForm::findOne(Yii::$app->user->getIdentity()->id);
        $content = Yii::$app->seo->getModel();

        if($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Successfully updated');
            $this->redirect(['//' . Yii::$app->seo->getModel()->route->getBreadcrumbs()]);
            return;
        }

        return $this->render('email', [
            'model' => $model,
            'content' => $content
        ]);
    }

    public function actionPassword() {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();

        try {
            $model = PasswordForm::findOne(Yii::$app->user->getIdentity()->id);
            $content = Yii::$app->seo->getModel();

            if($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Successfully updated');
                $this->redirect(['//' . Yii::$app->seo->getModel()->route->getBreadcrumbs()]);
                $transaction->commit();
                return;
            }
        }catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $transaction->rollBack();
        return $this->render('password', [
            'model' => $model,
            'content' => $content
        ]);
    }


    public function actionDelete() {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();

        try {
            $model = DeleteForm::findOne(Yii::$app->user->getIdentity()->id);
            $content = Yii::$app->seo->getModel();

            if($model->load(Yii::$app->request->post()) && Yii::$app->user->logout() && $model->delete()) {
                Yii::$app->session->setFlash('success', 'Your account have been deleted');
                $this->goHome();
                $transaction->commit();
                return;
            }
        }catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $transaction->rollBack();
        return $this->render('delete', [
            'model' => $model,
            'content' => $content
        ]);
    }
}
