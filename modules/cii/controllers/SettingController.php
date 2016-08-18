<?php

namespace app\modules\cii\controllers;

use Yii;
use cii\backend\BackendController as Controller;
use app\modules\cii\models\Configuration;
use yii\data\ArrayDataProvider;

class SettingController extends Controller {
    public function actionIndex() {
        /*
        $data = new ActiveDataProvider([
    		'query' => Configuration::find(), 
			'sort' => [
            	'attributes' => [
            		'name',
            		'value'
            	],
        	],
		]);*/
        $data = new ArrayDataProvider([
            'allModels' => Yii::$app->cii->package->getSettingTypes(),
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
        	'data' => $data
        ]);
    }

    public function actionUpdate($id, $key) {
        $pkg = Yii::$app->getModule($id);

        $model = Configuration::findOne([
            'extension_id' => $pkg->getIdentifier(),
            'name' => $key
        ]);

        if(!$model) {
            $model = new Configuration();
            $model->extension_id = $pkg->getIdentifier();
            $model->name = $key;
        }

        if($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/setting/index')]);
            return;
        }

        return $this->render('update', [
            'package' => $pkg,
            'model' => $model,
        ]);
    }

    public function actionDelete($id, $key) {
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
