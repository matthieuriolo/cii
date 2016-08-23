<?php
namespace cii\web;

use Yii;


class Controller extends \yii\web\Controller {
	protected $_package;
	
	public function init() {
		$this->layout = Yii::$app->layoutBasePath .
			'/' .
			Yii::$app->cii->setting('cii', 'frontend_layout') .
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

/*
	public function getSeoRoute() {

	}*/
}
