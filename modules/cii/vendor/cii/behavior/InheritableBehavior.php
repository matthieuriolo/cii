<?php

namespace cii\behavior;

use Yii;
use yii\base\Behavior;
use app\modules\cii\models\Classname;

/*
 * use this behavior to inherit attributes from a relation
 *
 * Example:
 * public function behaviors() {
 *      return [
 *          [
 *              'class' => 'cii\behavior\InheritableBehavior',
 *              'inheritProperties' => [
 *                  'name' => 'relation.name',
 *                  'enabled' => 'relation.subrelation.enabled'
 *              ]
 *          ]
 *      ];
 *  }
 */

class InheritableBehavior extends Behavior {
    public $inheritProperties = [];

    public function hasMethod($name) {
       if(substr($name, 0, 3) == 'get') {
            $name = strtolower(substr($name, 3));
            if(isset($this->inheritProperties[$name])) {
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

        return parent::getRelation($name, $throwException);
    }

    public function __get($name) {
        if(isset($this->inheritProperties[$name])) {
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
        if(isset($this->inheritProperties[$name])) {
            return true;
        }

        return parent::canGetProperty($name, $checkVars);
    }
    /*
    public function canSetProperty($name, $checkVars = true) {
        if(isset($this->inheritProperties[$name])) {
            return false;
        }

        return parent::canSetProperty($name, $checkVars);
    }*/
}
