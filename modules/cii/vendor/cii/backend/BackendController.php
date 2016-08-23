<?php
namespace cii\backend;

use Yii;
use cii\web\Controller;
use yii\filters\AccessControl;

class BackendController extends Controller {
	public $package;
	
	public function init() {
		$this->layout = Yii::$app->layoutBasePath .
			'/' .
			Yii::$app->cii->setting('cii', 'backend_layout') .
			'/backend'
		;
	}

	public function getAccessRoles() {
		return ['@'];
	}

	public function behaviors() {
		$roles = array_map(function($elem) {
			if(is_int($elem)) {
				return [$this->getPackage()->getIdentifier(), $elem];
			}

			return $elem;
		}, $this->getAccessRoles());

		return [
			'access' => [
	            'class' => AccessControl::className(),
	            'rules' => [
	                [
	                    'allow' => true,
	                    'roles' => $roles,
	                ],
	            ],
	        ],
	    ];
	}
}
