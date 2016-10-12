<?php

namespace app\modules\cii\controllers\layout;

use Yii;
use app\modules\cii\controllers\SettingBaseController;
use app\modules\cii\Permission;
use yii\web\NotFoundHttpException;

class SettingController extends SettingBaseController {
    public function getAccessRoles() {
        return [Permission::MANAGE_LAYOUT, Permission::MANAGE_EXTENSION, Permission::MANAGE_ADMIN];
    }

    public function actionUpdate($id, $type, $key) {
        $model = $this->getExtensionModel($id, $type);

        $this->redirectURL = [
            Yii::$app->seo->relativeAdminRoute('modules/cii/layout/view'),
            'id' => $model->id
        ];

        $this->breadcrumbs = [];
        $this->breadcrumbs[] = [
            'label' => Yii::p('cii', 'Layouts'),
            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/layout/index')]
        ];

        $this->breadcrumbs[] = [
            'label' => Yii::p('cii', 'Layout - ') . $model->getName(),
            'url' => $this->redirectURL
        ];
        return parent::actionUpdate($id, $type, $key);
    }

    public function actionDelete($id, $type, $key) {
        $model = $this->getExtensionModel($id, 'layout');
        if(!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $this->redirectURL = [
            Yii::$app->seo->relativeAdminRoute('modules/cii/layout/view'),
            'id' => $model->id
        ];

        return parent::actionDelete();
    }
}
