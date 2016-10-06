<?php


namespace cii\db\DbDumper;

use Yii;
use yii\base\Object;
use cii\helpers\UTC;

/*
 * Thanks to Andrii Bubis (firstrow)
 *
 * https://github.com/firstrow/yii-database-dumper/
 */
class MysqlDumper extends BaseDumper {
	public function exportToFile($file) {
		if($file = @fopen($file, 'w')) {
			fwrite($file, '--- ' . $this->getDBName() . '(' . $this->db->getDriverName() . ') dumped from UTC ' . UTC::datetime() . PHP_EOL . PHP_EOL);
			fwrite($file, 'SET FOREIGN_KEY_CHECKS = 0;' . PHP_EOL);
			
			foreach($this->tables() as $table) {
				fwrite($file, '--- create table ' . $table->fullName . ' ' . PHP_EOL . PHP_EOL);
				fwrite($file, $this->createTable($table) . PHP_EOL);
			}

			foreach($this->tables() as $table) {
				$addedComment = false;
				$num = null;
				$offset = 0;
				$limit = 100;
				while($num === null || $num === 100) {
					if(($data = $this->dataTable($table, $limit, $offset)) && is_array($data) && ($num = count($data))) {
						if(!$addedComment) {
							fwrite($file, PHP_EOL . PHP_EOL . '--- dump data table ' . $table->fullName . ' ' . PHP_EOL . PHP_EOL);
							$addedComment = true;
						}
						$offset += $num;
						fwrite($file,  implode(PHP_EOL, $data) . PHP_EOL);
					}else {
						$num = false;
					}
				}
			}


			fwrite($file, PHP_EOL . PHP_EOL . 'SET FOREIGN_KEY_CHECKS = 1;' . PHP_EOL);
			return fclose($file);
		}

		return false;
	}

    protected function tables() {
    	return $this->db->getSchema()->getTableSchemas();
    }

    protected function createTable($table) {
    	$schema = $this->db->createCommand(
    		'SHOW CREATE TABLE ' . $this->db->quoteTableName($table->fullName) . ';'
    		)
    		->queryOne();
    
    	return $schema["Create Table"] . ';' . PHP_EOL . PHP_EOL;
    }

    protected function dataTable($table, $limit, $offset = 0) {
    	$tableName = $table->fullName;

    	return $this->rowsToString(
    		$tableName,
    		$this->db->createCommand(
    			'SELECT * FROM ' . $this->db->quoteTableName($tableName) .
    			' LIMIT ' . $offset . ',' . $limit . ' ;'
    		)
    		->queryAll()
    	);
    }

    protected function rowsToString($tableName, $rows) {
    	$ret = [];
    	$db = $this->db;
    	foreach($rows as $column) {
    		$attrs = array_map(array($db, 'quoteColumnName'), array_keys($column));
    		$row = 'INSERT INTO ' . $db->quoteTableName($tableName) . ' (' . implode(', ', $attrs) . ') VALUES';
    		
    		$attrs = array_map(function($value) use($db) {
    			if($value === null) {
					return 'NULL';
				}else {
					return $db->quoteValue($value);
				}
    		}, array_values($column));

    		$row .= ' (' . implode(', ', $attrs) . ');';
    		$ret[] = $row;
    	}

    	return $ret;
    }

    protected function getDBName() {
    	$match = [];
        if (preg_match('/dbname=([^;]*)/', $this->db->dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }
}
