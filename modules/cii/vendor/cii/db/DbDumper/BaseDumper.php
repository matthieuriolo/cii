<?php


namespace cii\db\DbDumper;

use Yii;
use yii\base\Object;
use yii\di\Instance;
use yii\db\Connection;

abstract class BaseDumper extends Object {
	public $db;
	public $exportData = true;

	public function init() {
		$this->db = Instance::ensure($this->db, Connection::className());
		return parent::init();
	}

    abstract public function exportToFile($file);

    abstract protected function tables();
    abstract protected function createTable($name);
    abstract protected function dataTable($name, $limit, $offset = 0);
}
