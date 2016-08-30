<?php

namespace app\modules\cii\controllers\language;

use Yii;
use app\modules\cii\models\LanguageMessage;

use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use cii\backend\BackendController;

use cii\base\SearchModel;



class MessageController extends BackendController {
    public function actionView($id) {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'settings' => new ArrayDataProvider([
                'allModels' => $model->settings,
                'sort' => [
                    'attributes' => [
                        'label',
                        'id',
                        'type',
                        'default',
                        'value'
                    ]
                ]
            ])
        ]);
    }

    public function actionEnable($id) {
        $model = $this->findModel($id);
        $ext = $model->extension;
        $ext->enabled = true;
        $ext->save();

        Yii::$app->cii->language->clearCache();
        $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/language/view'), 'id' => $model->language_id]);
    }

    public function actionDisable($id) {
        $model = $this->findModel($id);
        $ext = $model->extension;
        $ext->enabled = false;
        $ext->save();

        Yii::$app->cii->language->clearCache();
        $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/language/view'), 'id' => $model->language_id]);
    }

    protected function findModel($id) {
        if (($model = LanguageMessage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
