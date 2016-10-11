<?php
namespace cii\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidValueException;
use cii\base\OrderModelInterface;
use yii\base\Exception;

class DownAction extends Action {
    public $idParam = 'id';
	public function run() {
        $model = $this->controller->findModel(Yii::$app->request->get($this->idParam));
        
        if(!($model instanceof OrderModelInterface)) {
            throw new InvalidValueException();
        }
        
        if(!$model->orderDown()) {
        	throw new Exception();
        }

        return $this->controller->goBackToReferrer();
    }
}
