<?php

namespace app\modules\cii\controllers;

use Yii;
use app\modules\cii\models\Layout;
use yii\data\ActiveDataProvider;

class LayoutController extends ExtensionBaseController {
    protected function getModelType() {
        return 'Layout';
    }

    protected function getModelUrl() {
        return [\Yii::$app->seo->relativeAdminRoute('modules/cii/layout/index')];
    }

    protected function getModel($id) {
        return Layout::find()->where(['id' => $id])->one();
    }

    protected function getDataProvider($searchModel = null) {
        $query = Layout::find()->joinWith('extension');
        
        if($searchModel) {
            if($searchModel->load(Yii::$app->request->get()) && $searchModel->validate()) {
                $query = $searchModel->applyFilter($query);
            }
        }

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'name',
                    'enabled',
                    'installed',
                    'type' => [
                        'asc' => ['classname_id' => SORT_ASC],
                        'desc' => ['classname_id' => SORT_DESC],
                    ]
                ],
            ],
        ]);
    }
}
