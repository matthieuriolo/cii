<?php

namespace app\modules\cii\controllers;

use Yii;
use app\modules\cii\models\Language;
use app\modules\cii\models\LanguageSearch;
use app\modules\cii\models\FormatterExample;

use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
/**
 * LanguageController implements the CRUD actions for Language model.
 */
class LanguageController extends ExtensionBaseController {
    protected function getModelType() {
        return 'Language';
    }

    protected function getModelUrl() {
        return [\Yii::$app->seo->relativeAdminRoute('modules/cii/language/index')];
    }

    protected function getModel($id) {
        return $this->findModel($id);
    }

    protected function getDataProvider() {
        return new ActiveDataProvider([
            'query' => Language::find(),
            'sort' => [
                'attributes' => [
                    'name',
                    'code',
                    'shortcode',
                    'enabled',
                ],
            ],
        ]);
    }

    public function actionCreate() {
        $model = new Language();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->cii->language->clearCache();
            $this->redirect([Yii::$app->seo->relativeAdminRoute('modules/cii/language/view'), ['id' => $model->id]]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    
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

    protected function findModel($id) {
        if (($model = Language::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*public function actionIndex() {
        $searchModel = new LanguageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    */

    public function actionView($id) {
        $language = $this->findModel($id);
        
        $model = new FormatterExample();
        $model->load(Yii::$app->request->post());
        $model->validate();
        $model->setLanguage($language);

        return $this->render('view', [
            'model' => $language,
            'formatterExample' => $model,
        ]);
    }
}
