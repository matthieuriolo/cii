<?php

namespace app\commands;

use yii\console\Controller;


/**
 * This command echoes sql commands in the migration format of yii
 *
 * Use this command if you want to convert an sql file with table schemas to the migration format
 *
 */
class MysqlConverterController extends Controller
{
    
    public function actionIndex($file) {
        if(!is_file($file)) {
        	throw new \Exception("First parameter is not a file");
        }

        if(!($content = file_get_contents($file)) || empty($content)) {
        	throw new \Exception("Could not read or empty file");
        }

        $matches = array();
        preg_match_all('/CREATE TABLE (IF NOT EXISTS)? ([^\(]+) \(([^;]*);/', $content, $matches, PREG_SET_ORDER);

        if(empty($matches)) {
        	throw new \Exception("No create statements found!");
    	}
        
        foreach ($matches as $value) {
    		$tableName = $this->getTableName($value[2]);
    		$fields = $this->parseTableFields($value[3]);

    		$this->printTable($tableName, $fields);
    	}
    }

    protected function printTable($tableName, $fields) {
    	echo "/* table ", $tableName, " */\n",
    		'$this->createTable(\'{{%' , $tableName, '}}\', [' . "\n";

    	if($fields['primary']) {
    		echo "\t'".$fields['primary']."' => \$this->primaryKey()->unsigned(),\n";
    	}

    	foreach($fields['columns'] as $field) {
    		if($fields['primary'] == $field['name']) {
    			continue;
    		}

    		echo "\t'".$field['name']."' => \$this->", $field['type'];
    		
    		if($field['isNotNull']) {
    			echo '->notNull()';
    		}

    		if(isset($field['isUnique'])) {
    			echo '->unique()';
    		}

    		if($field['default']) {
    			echo '->defaultValue('.$field['default'].')';
    		}

    		echo ",\n";
    	}

    	echo "], \$tableOptions);\n\n";
		
		if(count($fields['constraint'])) {
			foreach($fields['constraint'] as $constr) {
				echo '$this->addForeignKey(',  "\n",
					"\t'" , $constr['name'] , "',\n",
					"\t'" , $tableName , "',\n",
					"\t'" , $constr['column'] , "',\n",
					"\t'" , $constr['refTable'] , "',\n",
					"\t'" , $constr['refColumn'] , "',\n",
					"\t'" , $constr['delete'] , "',\n",
					"\t'" , $constr['update'] , "'\n",
				");\n\n";
			}
		}

		if(count($fields['index'])) {
			foreach($fields['index'] as $index) {
				echo '$this->createIndex(', "\n",
					"\t'" , $index['name'] , "',\n",
					"\t'" , $tableName , "',\n",
					"\t'" , $index['column'] , "'\n",
				");\n\n";
			}
		}
    }

    protected function parseTableFields($value) {
    	$ret = [
    		'columns' => [],
    		'index' => [],
    		'primary' => [],
    		'constraint' => [],
    	];

    	$value = preg_replace('/([^,])\n/', '$1 ', $value);
    	$lines = explode("\n", $value);

    	foreach($lines as $line) {
    		$line = trim($line);
    		if(empty($line)) {
    			continue;
    		}

    		$match = array();
    		if(stripos($line, 'UNIQUE INDEX ') !== false) {
    			if(preg_match('/\(([^\)]+)\)/', $line, $match)) {
    				$isUnique = explode(' ', $match[1]);
    				$isUnique = $this->trimQ(reset($isUnique));
    				$ret['columns'][$isUnique]['isUnique'] = true;
    			}
    		}else if(preg_match('/INDEX (\S+) \(([^\)]+)\)/', $line, $match)) {
    			$c = explode(' ', $match[2]);
    			$c = $this->trimQ(reset($c));
    			
    			$ret['index'][] = [
    				'name' => $this->getTableName($match[1]),
    				'column' => $c
    			];
    		}else if(preg_match('/CONSTRAINT (\S+)/', $line, $match)) {
    			$constr = [
    				'name' => $this->trimQ($match[1]),
    				'column' => '',
    				'refColumn' => '',
    				'refTable' => '',
    				'update' => '',
    				'delete' => '',
    			];

    			if(preg_match('/FOREIGN KEY \((\S+)\)/', $line, $match)) {
    				$constr['column'] = $this->trimQ($match[1]);
    				if(preg_match('/REFERENCES (\S+) \((\S+)\)/', $line, $match)) {
	    				$constr['refTable'] = $this->getTableName($match[1]);
	    				$constr['refColumn'] = $this->trimQ($match[2]);

	    				if(preg_match('/ON DELETE ((NO ACTION)|(RESTRICT)|(CASCADE)|(SET NULL))/', $line, $match)) {
	    					$constr['delete'] = $match[1];
	    				}

	    				if(preg_match('/ON UPDATE ((NO ACTION)|(RESTRICT)|(CASCADE)|(SET NULL))/', $line, $match)) {
	    					$constr['update'] = $match[1];
	    				}

	    				$ret['constraint'][] = $constr;
	    			}
    			}

    		}else if(preg_match('/PRIMARY KEY \(([^\)]+)\)/', $line, $match)) {
    			$ret['primary'] = $this->trimQ($match[1]);
    		}else {

    			$typeMapping = [
    				'tinyint(1)' => 'boolean',
    				'tinyint' => 'smallInteger',
    				'float' => 'float',
    				'int' => 'integer',
    				'varchar' => 'string',
    				'text' => 'text',
                    'datetime' => 'dateTime'
    			];

    			$type = null;
    			foreach($typeMapping as $orig => $map) {
    				if(stripos($line, $orig) !== false) {
    					$type = $map;
                        
                        if(preg_match('/'.$orig.'\s*\((\d+)\)/i', $line, $match)) {
    						$type .= '('.$match[1].')';
    					}else {
    						$type .= '()';
    					}

                        if(stripos($line, 'unsigned')!==false) {
                            $type .= '->unsigned()';
                        }
                        break;
    				}
    			}

    			if(is_null($type)) {
    				throw new \Exception("Could not parse line: " . $line);
    			}

    			$names = explode(' ', $line);
    			$name = $this->trimQ(reset($names));
    			
    			$ret['columns'][$name] = [
    				'name' => $name,
    				'isNotNull' => stripos($line, 'not null') !== false,
    				'default' => null,
    				'type' => $type
    			];
    		}
    	}

    	return $ret;
    }

    protected function trimQ($value) {
    	return trim($value, '`');
    }

    protected function getTableName($value) {
    	if(($pos = strpos($value, '.')) !== false) {
    		$value = substr($value, $pos+1);
    	}

    	return $this->trimQ($value);
    }
}
