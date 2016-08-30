<?php

namespace app\modules\cii\controllers;

use Yii;
use app\modules\cii\models\Language;
use app\modules\cii\models\LanguageMessage;
use app\modules\cii\models\FormatterExample;

use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use cii\backend\BackendController;

use cii\base\SearchModel;


class LanguageController extends BackendController {
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/language/view'), ['id' => $model->id]]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();
        Yii::$app->cii->language->clearCache();
        $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/language/index')]);
    }

    public function actionView($id) {
        $language = $this->findModel($id);
        
        $model = new FormatterExample();
        $model->load(Yii::$app->request->post());
        $model->validate();
        $model->setLanguage($language);

        $query = LanguageMessage::find();
        $query->where(['language_id' => $id]);
        $query->joinWith('translatedExtension as transl');
        $messages = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'enabled',
                    'installed',
                    'translatedExtension.name' => [
                        'asc' => ['transl.name' => SORT_ASC],
                        'desc' => ['transl.name' => SORT_DESC],
                    ],

                    'translatedExtension.type' => [
                        'asc' => ['transl.classname_id' => SORT_ASC],
                        'desc' => ['transl.classname_id' => SORT_DESC],
                    ]
                ],
            ],
        ]);

        return $this->render('view', [
            'model' => $language,
            'formatterExample' => $model,
            'messages' => $messages,
        ]);
    }

    public function actionIndex() {
        $model = new SearchModel(Language::className());
        $model->stringFilter('name', ['name', 'code', 'shortcode']);
        $model->booleanFilter('enabled');
        
        $query = Language::find();
        
        if($model->load(Yii::$app->request->get()) && $model->validate()) {
            $query = $model->applyFilter($query);
        }

        $data = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'name',
                    'code',
                    'shortcode',
                    'enabled',
                    'created'
                ],
            ],
        ]);


        return $this->render('index', [
            'data' => $data,
            'model' => $model,
        ]);
    }


    public function actionEnable($id) {
        $module = $this->findModel($id);
        $module->enabled = true;
        $module->save();

        Yii::$app->cii->language->clearCache();
        $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/language')]);
    }

    public function actionDisable($id) {
        $module = $this->findModel($id);
        $module->enabled = false;
        $module->save();

        Yii::$app->cii->language->clearCache();
        $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/language')]);
    }

    protected function findModel($id) {
        if (($model = Language::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
