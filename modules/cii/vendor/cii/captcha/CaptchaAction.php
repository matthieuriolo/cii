<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace cii\captcha;

use Yii;
use yii\captcha\CaptchaAction as CCaptchaAction;
use yii\web\Response;
use cii\helpers\Url;

class CaptchaAction extends CCaptchaAction {
    public $url;
    public $transparent = true;
    public $width = 60;
    public $height = 34;

    public $minLength = 3;
    public $maxLength = 5;

    public function run() {
        if (Yii::$app->request->getQueryParam(self::REFRESH_GET_VAR) !== null) {
            // AJAX request for regenerating code
            $code = $this->getVerifyCode(true);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'hash1' => $this->generateValidationHash($code),
                'hash2' => $this->generateValidationHash(strtolower($code)),
                // we add a random 'v' parameter so that FireFox can refresh the image
                // when src attribute of image tag is changed
                'url' => $this->url ? Url::to((array)$this->url + ['v' => uniqid()]) : Url::to([$this->id, 'v' => uniqid()]),
            ];
        } else {
            $this->setHttpHeaders();
            Yii::$app->response->format = Response::FORMAT_RAW;
            return $this->renderImage($this->getVerifyCode());
        }
    }
}
