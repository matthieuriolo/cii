<?php
namespace cii\web;

use Yii;


class Controller extends \yii\web\Controller {
	protected $_package;
	public $layout = '@core/views/layouts/main';

	public function init() {
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
