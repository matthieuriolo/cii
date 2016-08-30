<?php

namespace app\modules\cii\controllers;

use Yii;
use cii\backend\BackendController as Controller;
use app\modules\cii\models\Package;
use yii\data\ActiveDataProvider;

class PackageController extends ExtensionBaseController {
	protected function getModelType() {
        return 'Package';
    }

    protected function getModelUrl() {
        return [\Yii::$app->seo->relativeAdminRoute('modules/cii/package/index')];
    }

    protected function getModel($id) {
        return Package::find()->where(['id' => $id])->one();
    }

    protected function getDataProvider($searchModel = null) {
        $query = Package::find()->joinWith('extension');

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
                ],
            ],
        ]);
    }
}
