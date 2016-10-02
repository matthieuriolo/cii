<?php

namespace app\modules\cii\controllers;

use Yii;
use app\modules\cii\models\Layout;
use app\modules\cii\Permission;
use yii\data\ActiveDataProvider;

class LayoutController extends ExtensionBaseController {
    public $packageRoute = 'cii/layout/setting';

    public function getAccessRoles() {
        return [Permission::MANAGE_LAYOUT, Permission::MANAGE_EXTENSION, Permission::MANAGE_ADMIN];
    }

    public function actionEnable($id) {
        Yii::$app->cii->layout->clearCache();
        parent::actionEnable($id);
    }

    public function actionDisable($id) {
        Yii::$app->cii->layout->clearCache();
        parent::actionDisable($id);
    }

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
