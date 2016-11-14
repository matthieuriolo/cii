<?php

namespace app\modules\cii\controllers;

use Yii;

use cii\backend\BackendController;

use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\modules\cii\Permission;
use app\modules\cii\models\common\ContentVisibilities;

class PositionBaseController extends BackendController {
    public function actions() {
        return [
            'up' => [
                'class' => 'cii\actions\UpAction'
            ],

            'down' => [
                'class' => 'cii\actions\DownAction'
            ],
        ];
    }

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
        return [Permission::MANAGE_CONTENT, Permission::MANAGE_ADMIN];
    }

    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->delete();
        return $this->goBackToReferrer();
    }

    public function findModel($id) {
        if(($model = ContentVisibilities::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
