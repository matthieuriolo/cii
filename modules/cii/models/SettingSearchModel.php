<?php

namespace app\modules\cii\models;

use Yii;

use cii\base\SearchModel;
use app\modules\cii\models\extension\Configuration;

class SettingSearchModel extends SearchModel {
    public function __construct() {
        parent::__construct(Configuration::className());
    }
    
    public function getTypes() {
        return [
            null => Yii::p('cii', 'All'),
            'in' => Yii::p('cii', 'In'),
            'boolean' => Yii::p('cii', 'Boolean'),
            'float' => Yii::p('cii', 'Float'),
            'integer' => Yii::p('cii', 'Integer'),
            'text' => Yii::p('cii', 'Text'),
            'password' => Yii::p('cii', 'Password'),
            'image' => Yii::p('cii', 'Image'),
        ];
    }

    public function typeFilter($name, $attributes = null) {
        $values = $this->getTypes();

        $this->addAttribute($name, ['extension', 'template' => 'in', 'values' => $values], [
            ['in', 'range' => array_keys($values)]
        ], $attributes);
    }


    public function filterArray($data) {
        $model = $this;
        return array_filter($data, function($row) use($model) {
            if(!empty($model->name)) {
                if(
                    stripos($row->label, $model->name) === false 
                    &&
                    stripos($row->value, $model->name) === false
                ) {
                    return false;
                }
            }

            if(!empty($model->type)) {
                if($row->type != $model->type) {
                    return false;
                }
            }

            return true;
        });
    }
}