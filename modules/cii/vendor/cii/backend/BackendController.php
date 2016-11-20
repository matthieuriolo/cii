<?php
namespace cii\backend;

use Yii;
use cii\web\Controller;
use yii\filters\AccessControl;
use yii\base\InvalidConfigException;

class BackendController extends Controller {
	public $package;

	public function getAccessRoles() {
		return ['@'];
	}

	public function behaviors() {
		$rules = [];
		foreach($this->getAccessRoles() as $role) {
			if(is_array($role)) {
				$rs = [];
				foreach($role['roles'] as $r) {
					if(is_int($r)) {
						$rs[] = [$this->getPackage()->getIdentifier(), $r];
					}else {
						$rs[] = $r;
					}
				}

				$role['roles'] = $rs;
				$rules[] = $role;
			}else if(is_int($role)) {
				$rules[] = [
                    'allow' => true,
                    'roles' => [[$this->getPackage()->getIdentifier(), $role]],
                ];
			}else {
				$rules[] = [
                    'allow' => true,
                    'roles' => [$role],
                ];
			}
		}
		
		return [
			'access' => [
	            'class' => AccessControl::className(),
	            'rules' => $rules
	        ],
	    ];
	}
}
