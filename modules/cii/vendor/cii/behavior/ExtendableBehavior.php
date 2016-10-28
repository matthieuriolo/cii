<?php

namespace cii\behavior;

use Yii;
use yii\base\Behavior;
use app\modules\cii\models\Classname;


class ExtendableBehavior extends Behavior {
    public $throwErrorUnboxed = false;
    public $inheritProperties = [];

    public function outbox() {
        if($ret = $this->getExtendedModel()) {
            if(method_exists($ret, 'outbox')) {
                return $ret->outbox();
            }
            
            return $ret;
        }

        if($this->throwErrorUnboxed) {
            throw new \Exception("Model is unboxed and cannot be outboxed");
        }

        return $this->owner;
    }

    public function getExtendedModel() {
        $model = $this->owner->classname;

        $model = $model->path;
        if(!$model::canOutboxFrom($this->owner)) {
            return null;
        }

        return $this->owner->hasOne($model::className(), [$model::getOutboxAttribute($this->owner) => 'id'])->one();
    }


    public function getClassname() {
        return $this->owner->hasOne(Classname::className(), ['id' => 'classname_id']);
    }

    /* create virtual getters for the relations from outboxModels and fromModels */

    /* old system 
    
    public $outboxModels = [];
    public $fromModels = [];
    
    public function cast() {
        $model = $this->getClassname()->path;
        return $model::find()->where(['' => $this->owner->id])->one();
        
        foreach(array_keys($this->outboxModels) as $relation) {
            if($this->owner->$relation) {
                return $this->owner->$relation;
            }
        }

        return null;
    }

    public function hasMethod($name) {
       if(substr($name, 0, 3) == 'get') {
            $name = strtolower(substr($name, 3));
            if(isset($this->fromModels[$name]) || isset($this->outboxModels[$name]) || isset($this->inheritProperties[$name])) {
                return true;
            }
        }

        return parent::hasMethod($name);
    }
    
    public function __call($name, $throwException = true) {
        $name = strtolower(substr($name, 3));
        if($this->canGetProperty($name)) {
            return $this->$name;
        }
        var_dump($name);
        return parent::getRelation($name, $throwException);
    }

    public function __get($name) {

        if(isset($this->fromModels[$name])) {
            $model = $this->fromModels[$name];
            return $this->owner->hasOne($model['class'], [$model['class']::primaryKey()[0] => $model['property']])->inverseOf($model['reverseProperty']);
        } else if(isset($this->outboxModels[$name])) {
            $model = $this->outboxModels[$name];
            //return $this->owner->hasMany($model['class'], [$model['property'] => $model['class']::primaryKey()[0]])->inverseOf($model['reverseProperty'])->one();
            return $this->owner->hasOne($model['class'], [$model['property'] => $model['class']::primaryKey()[0]])->inverseOf($model['reverseProperty']);
        } else if(isset($this->inheritProperties[$name])) {
            $prop = $this->inheritProperties[$name];
            $node = $this->owner;

            while(($pos = strpos($prop, '.')) !== false) {
                $p = substr($prop, 0, $pos);
                $node = $node->$p;
                $prop = substr($prop, $pos + 1);
            }

            return $node->$prop;
        }

        return parent::__get($name);
    }

    public function canGetProperty($name, $checkVars = true) {
        if(isset($this->fromModels[$name]) || isset($this->outboxModels[$name]) || isset($this->inheritProperties[$name])) {
            return true;
        }

        return parent::canGetProperty($name, $checkVars);
    }

    public function canSetProperty($name, $checkVars = true) {
        if(isset($this->fromModels[$name]) || isset($this->outboxModels[$name])) {
            return false;
        }

        return parent::canSetProperty($name, $checkVars);
    } */
}
