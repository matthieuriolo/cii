<?php
namespace cii\web;

use Yii;

class Application extends \yii\web\Application {
	public $seo = null;


	public function init() {
		//$this->setModules(Yii::$app->cii->packages->all(true));
		//$pkg->name, 'app\modules\\' . $pkg->name . '\Module');
		
		return parent::init();
	}


    public function getModulePath() {
        return $this->basePath . '/' . 'modules';
    }





    public function handleRequest($request) {
        $seo = null;

        if (empty($this->catchAll)) {
            $tmp = $request->resolve();
            list($route, $params) = $tmp;
            if(isset($tmp[2])) {
                $seo = $tmp[2];
            }
        } else {
            $route = $this->catchAll[0];
            $params = $this->catchAll;
            unset($params[0]);
        }

        try {
            Yii::trace("Route requested: '$route'", __METHOD__);
            $this->requestedRoute = $route;
            $this->seo = $seo;
            //Yii::setAlias('@seo', $seo->getBaseRoute());

            
            $result = $this->runAction($route, $params);
            if ($result instanceof Response) {
                return $result;
            } else {
                $response = $this->getResponse();
                if ($result !== null) {
                    $response->data = $result;
                }

                return $response;
            }
        } catch (InvalidRouteException $e) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'), $e->getCode(), $e);
        }
    }
}
