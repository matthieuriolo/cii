<?php

namespace app\modules\cii\models\auth;

use Yii;
use yii\base\Model;
use app\modules\cii\models\Permission as APermission;

class PermissionForm extends Model {
    public $value;

    public function rules() {
        return [
            [['value'], 'required'],
            [['value'], 'string'],

            [['value'], 'validePermission'],
        ];
    }


    public function validePermission($attribute, $params)  {
        $val = $this->$attribute;

        $val = explode('-', $val);
        if(count($val) == 2) {
            list($module, $permission) = $val;
            if($module = Yii::$app->getModule($module)) {
                if(in_array($permission, array_keys($module->getPermissionTypes()))) {
                    return;
                }
            }
        }

        $this->addError($attribute, 'Invalid Permission');
    }

    public function assign($groupId) {
        list($module, $permission) = explode('-', $this->value);

        $perm = new APermission();
        $perm->group_id = $groupId;
        $perm->permission_id = $permission;
        $perm->package_id = Yii::$app->getModule($module)->getIdentifier();
        $perm->save();

        $this->value = null;
    }

    public function attributeLabels() {
        return [
            'value' => Yii::p('cii', 'Permission')
        ];
    }

}
