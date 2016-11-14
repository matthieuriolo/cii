<?php

namespace cii\rbac;

use app\modules\cii\models\auth\Permission;
use yii\base\Component;
use yii\di\Instance;
use yii\db\Connection;


class DbManager extends Component {
    public $db = 'db';

    public function init() {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());
        
    }
    
    public function checkAccess($userId, $packageId, $permissionId, $params = []) {
        $perm = Permission::find()
            ->joinWith(['group as g', 'group.members as m'])
            ->where([
                'permission_id' => $permissionId,
                'package_id' => $packageId,
                'g.enabled' => true,
                'm.user_id' => $userId
            ])
            ->one()
        ;
        
        return $perm !== null;
    }
}
