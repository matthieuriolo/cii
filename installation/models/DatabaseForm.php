<?php

namespace app\models;

use Yii;
use yii\base\Model;

class DatabaseForm extends Model
{
    public $mode = 'mysql';
    public $host = 'localhost';
    public $dbname = 'cii';
    public $username = 'root';
    public $password;

    public function rules()
    {
        return [
            ['mode', 'in', 'range' => $this->modes()],
            [['host', 'dbname', 'username'], 'required'],
            ['mode', 'testConnection'],
            ['dbname', 'testDatabase']
        ];
    }

    public function testConnection($attribute,$params) {
        try{
            $connection = new \yii\db\Connection([
                'dsn' => $this->mode . ':host='.$this->host,
                'username' => $this->username,
                'password' => $this->password,
            ]);

            $connection->open();
        }catch(\Exception $e) {
            $this->addError($attribute, 'DB cannot connected');
        }
    }

    public function testDatabase($attribute,$params) {
        try{
            $connection = new \yii\db\Connection([
                'dsn' => $this->mode . ':host='.$this->host.';dbname='.$this->dbname,
                'username' => $this->username,
                'password' => $this->password,
            ]);
            
            $connection->open();
        }catch(\Exception $e) {
            $this->addError($attribute, 'The database does not exist');
        }
    }

    public function namedModes() {
        return [
            'mysql' => 'MySQL',
            'postgres' => 'PostgreSQL'
        ];
    }

    public function modes() {
        return array_keys($this->namedModes());
    }


    public function attributeLabels() {
        return [
            'mode' => 'Select a database type',
            'host' => 'Host',
            'username' => 'User',
            'password' => 'Password',
            'dbname' => 'Database name',
            
        ];
    }
}
