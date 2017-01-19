<?php

namespace cii\behavior;

use Yii;
use yii\base\Behavior;
use yii\db\Exception;
use yii\db\Expression;
use yii\db\BaseActiveRecord;

class CounterSubBehavior extends Behavior {
    public $counterAttribute;
    public $modelAttribute;
    public $model;
    public $throwMissingException = false;


    public function events() {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'increaseAttribute',
            BaseActiveRecord::EVENT_BEFORE_DELETE => 'decreaseAttribute',
        ];
    }

    public function increaseAttribute($event) {
        $this->changeCounter(true);
    }

    public function decreaseAttribute($event) {
        $this->changeCounter(false);
    }

    protected function changeCounter($positive) {
        $model = $this->model;
        $modelAttribute = $this->modelAttribute;
        $data = $model::findOne($this->owner->$modelAttribute);

        if(!$data) {
            if($this->throwMissingException) {
                throw new Exception('Could not find model for counter'); 
            }

            return;
        }

        $counterAttribute = $this->counterAttribute;
        $data->$counterAttribute = new Expression($counterAttribute . ($positive ? ' + 1' : ' - 1'));
        $data->save();
    }
}
