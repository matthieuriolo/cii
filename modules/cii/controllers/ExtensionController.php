<?php

namespace app\modules\cii\controllers;

use Yii;
use app\modules\cii\models\extension\Extension;
use app\modules\cii\Permission;


use yii\data\ActiveDataProvider;

class ExtensionController extends ExtensionBaseController {
    public function getAccessRoles() {
        return [Permission::MANAGE_EXTENSION, Permission::MANAGE_ADMIN];
    }

    public function actionEnable($id) {
        Yii::$app->cii->clearCache();
        parent::actionEnable($id);
    }

    public function actionDisable($id) {
        Yii::$app->cii->clearCache();
        parent::actionDisable($id);
    }

    protected function getModelType() {
        return 'Extension';
    }

    protected function getModelUrl() {
        return [\Yii::$app->seo->relativeAdminRoute('modules/cii/extension/index')];
    }

    protected function getModel($id) {
        return Extension::find()->where(['id' => $id])->one();
    }

    protected function getDataProvider($searchModel = null) {
        $query = Extension::find();
        
        if($searchModel) {
            $searchModel->extensionTypeFilter('type', 'classname_id');

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
