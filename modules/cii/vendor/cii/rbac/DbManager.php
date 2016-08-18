<?php

namespace yii\filters;

use app\modules\cii\models\Permission;

class DbManager extends Component {
    public function init() {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());
        
    }
    
    public function checkAccess($userId, $packageId, $permissionId, $params = []) {
        $perm = Permission::find()
            ->joinWith(['group as g', 'g.members as m'])
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
