<?php

namespace app\modules\cii\controllers;

use Yii;
use cii\backend\BackendController;
use app\modules\cii\models\Configuration;
use yii\base\InvalidConfigException;

abstract class SettingBaseController extends BackendController {
    public $redirectURL = null;
    public $breadcrumbs = [];

    public function init() {
        $this->redirectURL = [Yii::$app->seo->relativeAdminRoute('modules/cii/setting/index')];
        $this->breadcrumbs[] = [
            'label' => Yii::p('cii', 'Settings'),
            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/setting/index')]
        ];
        return parent::init();
    }

    protected function getExtensionModel($id, $type) {
        $extension = 'app\modules\cii\models\\' . ucfirst($type);
        return $extension::find()
            ->joinWith('extension as ext')
            ->where([
                'ext.name' => $id
            ])
            ->one();
    }


    public function actionUpdate($id, $type, $key) {
        $identifier = null;

        $model = $this->getExtensionModel($id, $type);

        if($model) {
            $identifier = $model->extension_id;
        }
        

        if(!$identifier) {
            throw new InvalidConfigException();
        }
        

        $model = Configuration::findOne([
            'extension_id' => $identifier,
            'name' => $key
        ]);

        if(!$model) {
            $model = new Configuration();
            $model->extension_id = $identifier;
            $model->name = $key;
        }

        if($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect($this->redirectURL);
        }
        
        return $this->render('/setting/update', [
            'model' => $model,
            'redirectURL' => $this->redirectURL,
            'breadcrumbs' => $this->breadcrumbs,
        ]);
    }

    public function actionDelete($id, $type, $key) {
        $pkg = Yii::$app->getModule($id);
        if($model = Configuration::findOne([
            'extension_id' => $pkg->getIdentifier(),
            'name' => $key
        ])) {
            $model->delete();
        }

        $this->redirect($this->redirectURL);
        return;
    }
}
