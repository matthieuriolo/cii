<?php


namespace cii\db;

use Yii;
use yii\base\Component;

class DbDumper extends Component {
    static public function getInstance($db) {
        return Yii::createObject([
            'class' => 'cii\db\DbDumper\\' . ucfirst($db->driverName) . 'Dumper',
            'db' => $db,
        ]);
    }
}
