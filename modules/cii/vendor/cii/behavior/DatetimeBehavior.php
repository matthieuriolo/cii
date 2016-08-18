<?php

namespace cii\behavior;

use Yii;
use yii\db\Expression;
use yii\db\BaseActiveRecord;
use yii\base\InvalidCallException;
use yii\behaviors\AttributeBehavior;


class DatetimeBehavior extends AttributeBehavior {
    public $create = [];
    public $update = [];
    public $value;

    public function init() {
        parent::init();

        $create = (array)$this->create;
        $update = (array)$this->update;
        
        if(empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => $create + $update,
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $update,
            ];
        }

        if(empty($this->value)) {
            $this->value = new Expression('NOW()');
        }
    }

    public function touch($attribute) {
        $owner = $this->owner;
        if($owner->getIsNewRecord()) {
            throw new InvalidCallException('Updating the timestamp is not possible on a new record.');
        }

        $owner->updateAttributes(array_fill_keys((array) $attribute, $this->value));
    }
}
