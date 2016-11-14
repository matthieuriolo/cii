<?php


namespace app\modules\cii\models\auth;

use Yii;
use cii\base\Model;
use yii\captcha\Captcha;

class LogoutForm extends Model {
    public $authKey;
    
    public function init() {
        $this->authKey = Yii::$app->user->getIdentity()->getAuthKey();
    }

    public function rules() {
        return [
            [['authKey'], 'required'],
            [['authKey'], 'string'],
            [['authKey'], 'validateAuthKey']
        ];
    }

    public function validateAuthKey($attribute, $params) {
        $val = $this->$attribute;
        if($user = Yii::$app->user->getIdentity()) {
            if($user->validateAuthKey($val)) {
                return;
            }
        }

        $this->addError($attribute, 'Invalid auth key');
    }
}
