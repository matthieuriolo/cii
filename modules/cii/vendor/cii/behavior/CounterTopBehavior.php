<?php

namespace cii\behavior;

use Yii;
use yii\base\Behavior;


class CounterTopBehavior extends Behavior {
    public $counterMap = [];
    
    public function resetCounter($attribute, $relation) {
        $query = $this->owner->$relation;
        $this->owner->$attribute = $query->count();
        $this->owner->save();
    }


    public function resetAllCounter() {
        foreach($this->counterMap as $attribute => $relation) {
            $this->resetCounter($attribute, $relation);
        }
    }
}
