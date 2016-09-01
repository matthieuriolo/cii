<?php
namespace cii\web;

use Yii;


class Controller extends \yii\web\Controller {
	protected $_package;
	
	public function init() {
		$this->layout = Yii::$app->layoutBasePath .
			'/' .
			Yii::$app->cii->package->setting('cii', 'frontend_layout') .
			'/frontend'
		;
		$this->setView(Yii::createObject('cii\web\view', []));
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

	public function renderShadow($view, $params = []) {
		$tmpLayout = $this->layout;
		$this->layout = Yii::$app->layoutBasePath .
			'/' .
			Yii::$app->cii->package->setting('cii', 'frontend_layout') .
			'/content'
		;
        
        $content = $this->getView()->render($view, $params, $this);
        $content = $this->renderContent($content);
        $this->layout = $tmpLayout;
        
        return $content;
    }

/*
	public function getSeoRoute() {

	}*/
}
