<?php
namespace cii\web;

use Yii;


class Controller extends \yii\web\Controller {
	protected $_package;
	
	public function init() {
		$this->layout = Yii::$app->layoutBasePath .
			'/' .
			Yii::$app->cii->package->setting('cii', 'layout') .
			'/main'
		;
		$this->setView(Yii::createObject('cii\web\view', []));

		return parent::init();
	}

	public function getPackage() {
		if(!$this->_package) {
			foreach($this->getModules() as $module) {
				if($module instanceof \cii\base\Package) {
					return $this->_package = $module;
				}
			}
		}

		return $this->_package;
	}

	public function goBackToReferrer() {
		/*
		 * Thanks to djfly
		 *
		 * https://github.com/yiisoft/yii2/issues/4343
		 * 
		 */

		if(Yii::$app->request->referrer){
			return $this->redirect(Yii::$app->request->referrer);
		}else{
			return $this->goHome();
		}
	}

	public function renderShadow($view, $params = []) {
		$tmpLayout = $this->layout;
		$this->layout = Yii::$app->layoutBasePath .
			'/' .
			Yii::$app->cii->package->setting('cii', 'layout') .
			'/content'
		;
        
        $content = $this->getView()->render($view, $params, $this);
        $content = $this->renderContent($content);
        $this->layout = $tmpLayout;
        
        return $content;
    }

    public function renderRaw($view, $params = []) {
    	$tmpLayout = $this->layout;
		$this->layout = Yii::$app->layoutBasePath .
			'/' .
			Yii::$app->cii->package->setting('cii', 'layout') .
			'/raw'
		;
        
        $content = $this->getView()->render($view, $params, $this);
        $content = $this->renderContent($content);
        $this->layout = $tmpLayout;
        
        return $content;
    }
}
