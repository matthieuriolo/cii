<?php
namespace cii\web;

use Yii;
use cii\web\routes\DBRoute;

class Application extends \yii\web\Application {
	public $seo;
    public $modulePath = '@app/modules';
    public $layoutBasePath = '@app/layouts';
    public $messagePath = '@app/messages';
    
	public function init() {
        //set active modules
		$this->setModules($this->cii->package->moduleInitializerList());
        
        //set up mailer according to the settings (default is sendmail)
        if($this->cii->package->setting('cii', 'transport.type') == 'file') {
            $this->mailer->useFileTransport = true;
        }else if($this->cii->package->setting('cii', 'transport.type') == 'smtp') {
            $this->mailer->transport = Yii::createObject([
                'class' => 'Swift_SmtpTransport',
                'host' => $this->cii->package->setting('cii', 'transport.smtp.host'),
                'username' => $this->cii->package->setting('cii', 'transport.smtp.user'),
                'password' => $this->cii->package->setting('cii', 'transport.smtp.password'),
                'port' => $this->cii->package->setting('cii', 'transport.smtp.port'),
                'encryption' => $this->cii->package->setting('cii', 'transport.smtp.encryption'),
            ]);
        }

        //set up language
        if($language = $this->cii->language->getActiveLanguage()) {
            $this->language = $language->code;
            $this->formatter->initValuesFromLanguage($language);
        }

        //set up mail layout
        $layout = $this->cii->package->setting('cii', 'mail_layout');
        $this->mailer->htmlLayout = $this->layoutBasePath . '/' . $layout . '/mail-html';
        $this->mailer->textLayout = $this->layoutBasePath . '/' . $layout . '/mail-text';


		return parent::init();
	}


    public function coreComponents() {
        $components = parent::coreComponents();
        $components['i18n'] = ['class' => 'cii\i18n\I18N'];
        return $components;
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
            
            if($seo) {
                $model = $seo->findRoute(DBRoute::className());
                $model->increaseCounter();
            }

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
