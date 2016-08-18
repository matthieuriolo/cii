<?php


namespace cii\db;

use yii\base\Component;
use yii\di\Instance;
use yii\db\Connection;

abstract class Migration extends Component implements MigrationInterface
{
    use \yii\db\SchemaBuilderTrait;
    use CommandBuilderTrait;

    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection
     * that this migration should work with. Starting from version 2.0.2, this can also be a configuration array
     * for creating the object.
     *
     * Note that when a Migration object is created by the `migrate` command, this property will be overwritten
     * by the command. If you do not want to use the DB connection provided by the command, you may override
     * the [[init()]] method like the following:
     *
     * ```php
     * public function init()
     * {
     *     $this->db = 'db2';
     *     parent::init();
     * }
     * ```
     */
    public $db = 'db';


    /**
     * Initializes the migration.
     * This method will set [[db]] to be the 'db' application component, if it is `null`.
     */
    public function init()
    {
        parent::init();
        
        $this->db = Instance::ensure($this->db, Connection::className());
        //$this->db->getSchema()->refresh();
    }

    /**
     * @inheritdoc
     * @since 2.0.6
     */
    protected function getDb()
    {
        return $this->db;
    }

    protected function getCommand($sql = null) {
        return $this->db->createCommand($sql);
    }

    public function safeUp() {
        $transaction = $this->db->beginTransaction();
        try {
            $this->up();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }


    public function safeDown()
    {
        $transaction = $this->db->beginTransaction();
        try {
            $this->down();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
