<?php

namespace app\modules\cii\controllers;

use Yii;
use cii\backend\BackendController as Controller;

use app\modules\cii\models\SettingSearchModel;
use app\modules\cii\models\Configuration;

use yii\data\ArrayDataProvider;
use yii\base\InvalidConfigException;

class SettingController extends Controller {
    public function actionIndex() {
        $models = Yii::$app->cii->getSettingTypes();
        
        $model = new SettingSearchModel();
        $model->stringFilter('name', ['name', 'value']);
        $model->typeFilter('type');

        if($model->load(Yii::$app->request->get()) && $model->validate()) {
            $models = $model->filterArray($models);
        }

        $data = new ArrayDataProvider([
            'allModels' => $models,
            'sort' => [
                'attributes' => [
                    'label',
                    'id',
                    'type',
                    'default',
                    'value'
                ]
            ]
        ]);
        
        return $this->render('index', [
        	'data' => $data,
            'model' => $model
        ]);
    }

    public function actionUpdate($id, $type, $key) {
        $identifier = null;

        $extension = 'app\modules\cii\models\\' . ucfirst($type);
        $model = $extension::find()
            ->joinWith('extension as ext')
            ->where([
                'ext.name' => $id
            ])
            ->one();
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
            $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/setting/index')]);
            return;
        }

        return $this->render('update', [
            'model' => $model,
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

        $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/setting/index')]);
        return;
    }
}
