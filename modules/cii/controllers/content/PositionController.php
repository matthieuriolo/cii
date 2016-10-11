<?php

namespace app\modules\cii\controllers;

use Yii;
use app\modules\cii\models\RedirectRoute;
use app\modules\cii\models\ContentRoute;
use app\modules\cii\models\Content;
use app\modules\cii\models\ContentSearch;
use app\modules\cii\models\Classname;
use app\modules\cii\models\ContentVisibilities;

use cii\web\SecurityException;
use cii\backend\BackendController;
use cii\helpers\SPL;
use cii\base\SearchModel;


use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;


class PositionController extends BackendController {
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
    
    public function getAccessRoles() {
        return [Permission::MANAGE_CONTENT, Permission::MANAGE_ADMIN];
    }
    

    public function actionCreate() {
        $visibleModel = new ContentVisibilities();

        if($visibleModel->load(Yii::$app->request->post())) {
            $visibleModel->content_id = $id;
            if($visibleModel->save()) {
                $visibleModel = new ContentVisibilities();
            }
        }

    }

    public function findModel($id) {
        if (($model = ContentVisibilities::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
