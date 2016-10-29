<?php
namespace cii\web;

use Yii;
use yii\web\NotFoundHttpException;

class Request extends \yii\web\Request {
    

	 /**
     * Resolves the current request into a route and the associated parameters.
     * @return array the first element is the route, and the second is the associated parameters.
     * @throws NotFoundHttpException if the request cannot be resolved.
     */
    public function resolve()
    {
        $result = Yii::$app->getUrlManager()->parseRequest($this);
        if ($result !== false) {
            list ($route, $params, $seo) = $result;
            if ($this->_queryParams === null) {
                $_GET = $params + $_GET; // preserve numeric keys
            } else {
                $this->_queryParams = $params + $this->_queryParams;
            }
            return [$route, $this->getQueryParams(), $seo];
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
    }



    public function pjaxid() {
        $headers = $this->getHeaders();
        
        if($this->getIsPjax() && $headers->get('X-Pjax-Container')) {
            return substr(explode(' ', $headers->get('X-Pjax-Container'))[0], 1);
            //return $_SERVER['HTTP_X_PJAX'];
        }

        return null;
    }
}
