<?php
namespace cii\web;

use Yii;
class User extends \yii\web\User {
	public function can($permissionName, $params = [], $allowCaching = true) {
        //grant all rights to super user
        if($this->getIdentity() && $this->getIdentity()->superadmin) {
			return true;
		}

        if(($manager = $this->getAuthManager()) === null) {
            return false;
        }

        if(!is_array($permissionName)) {
        	throw new \Exception('You have to pass an array to User->can');
        }

        list($packageId, $permission) = $permissionName;
        return $manager->checkAccess($this->getId(), $packageId, $permission, $params);
    }
}
