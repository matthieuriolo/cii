<?php

namespace app\modules\cii\controllers\package;

use Yii;
use app\modules\cii\controllers\SettingBaseController;
use app\modules\cii\Permission;

class SettingController extends SettingBaseController {
    public function getAccessRoles() {
        return [Permission::MANAGE_PACKAGE, Permission::MANAGE_ADMIN];
    }

    public function actionUpdate($id, $type, $key) {
        $model = $this->getExtensionModel($id, $type);

        $this->redirectURL = [
            Yii::$app->seo->relativeAdminRoute('modules/cii/package/view'),
            'id' => $model->id
        ];

        $this->breadcrumbs = [];
        $this->breadcrumbs[] = [
            'label' => Yii::p('cii', 'Packages'),
            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/package/index')]
        ];

        $this->breadcrumbs[] = [
            'label' => Yii::p('cii', 'Package - ') . $model->getName(),
            'url' => $this->redirectURL
        ];
        return parent::actionUpdate($id, $type, $key);
    }

    public function actionDelete($id, $type, $key) {
        $this->redirectURL = [
            Yii::$app->seo->relativeAdminRoute('modules/cii/package/view'),
            'id' => $model->id
        ];

        return parent::actionDelete();
    }
}
