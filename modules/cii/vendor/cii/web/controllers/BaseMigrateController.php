<?php


namespace cii\web\controllers;

use Yii;
use Exception;
use yii\web\Controller;
use yii\helpers\FileHelper;

/**
 * BaseMigrateController is base class for migrate controllers.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
abstract class BaseMigrateController extends Controller
{
    
    public $migrationPath = '@app/migrations';
    


    public function options($actionID)
    {
        return array_merge(
            parent::options($actionID),
            ['migrationPath'] // global for all actions
        );
    }

    protected function canMigrate() {
        $path = Yii::getAlias($this->migrationPath);
        if (!is_dir($path)) {
            return false;
        }

        return true;
    }

    protected function migrateUpAll($limit = 0)
    {
        $migrations = $this->getNewMigrations();
        if (empty($migrations)) {
            return self::EXIT_CODE_NORMAL;
        }

        $total = count($migrations);
        $limit = (int) $limit;
        if ($limit > 0) {
            $migrations = array_slice($migrations, 0, $limit);
        }

        

        foreach ($migrations as $migration) {
            $this->stdout("\t$migration\n");
        }
        $this->stdout("\n");

        $applied = 0;
        
        foreach ($migrations as $migration) {
            if (!$this->migrateUp($migration)) {
                return self::EXIT_CODE_ERROR;
            }
            $applied++;
        }

        return 0;
    }

    protected function migrateDownAll($limit = 1)
    {
        if ($limit === 'all') {
            $limit = null;
        } else {
            $limit = (int) $limit;
            if ($limit < 1) {
                throw new Exception('The step argument must be greater than 0.');
            }
        }

        $migrations = $this->getMigrationHistory($limit);

        if (empty($migrations)) {
            return self::EXIT_CODE_NORMAL;
        }

        $migrations = array_keys($migrations);
        $reverted = 0;
        foreach ($migrations as $migration) {
            if (!$this->migrateDown($migration)) {
                return self::EXIT_CODE_ERROR;
            }
            $reverted++;
        }


        return 0;
    }

    

    /**
     * Upgrades or downgrades till the specified version.
     *
     * Can also downgrade versions to the certain apply time in the past by providing
     * a UNIX timestamp or a string parseable by the strtotime() function. This means
     * that all the versions applied after the specified certain time would be reverted.
     *
     * This command will first revert the specified migrations, and then apply
     * them again. For example,
     *
     * ```
     * yii migrate/to 101129_185401                      # using timestamp
     * yii migrate/to m101129_185401_create_user_table   # using full name
     * yii migrate/to 1392853618                         # using UNIX timestamp
     * yii migrate/to "2014-02-15 13:00:50"              # using strtotime() parseable string
     * ```
     *
     * @param string $version either the version name or the certain time value in the past
     * that the application should be migrated to. This can be either the timestamp,
     * the full name of the migration, the UNIX timestamp, or the parseable datetime
     * string.
     * @throws Exception if the version argument is invalid.
     */
    protected function migrateTo($version)
    {
        if (preg_match('/^m?(\d{6}_\d{6})(_.*?)?$/', $version, $matches)) {
            $this->migrateToVersion('m' . $matches[1]);
        } elseif ((string) (int) $version == $version) {
            $this->migrateToTime($version);
        } elseif (($time = strtotime($version)) !== false) {
            $this->migrateToTime($time);
        } else {
            throw new Exception("The version argument must be either a timestamp (e.g. 101129_185401),\n the full name of a migration (e.g. m101129_185401_create_user_table),\n a UNIX timestamp (e.g. 1392853000), or a datetime string parseable\nby the strtotime() function (e.g. 2014-02-15 13:00:50).");
        }
    }

    

    /**
     * Upgrades with the specified migration class.
     * @param string $class the migration class name
     * @return boolean whether the migration is successful
     */
    protected function migrateUp($class)
    {
        if ($class === self::BASE_MIGRATION) {
            return true;
        }

        
        $start = microtime(true);
        $migration = $this->createMigration($class);
        if ($migration->up() !== false) {
            $this->addMigrationHistory($class);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Downgrades with the specified migration class.
     * @param string $class the migration class name
     * @return boolean whether the migration is successful
     */
    protected function migrateDown($class)
    {
        if ($class === self::BASE_MIGRATION) {
            return true;
        }

        $start = microtime(true);
        $migration = $this->createMigration($class);
        if ($migration->down() !== false) {
            $this->removeMigrationHistory($class);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Creates a new migration instance.
     * @param string $class the migration class name
     * @return \yii\db\MigrationInterface the migration instance
     */
    protected function createMigration($class)
    {
        $file = $this->migrationPath . DIRECTORY_SEPARATOR . $class . '.php';
        require_once($file);

        return new $class();
    }

    /**
     * Migrates to the specified apply time in the past.
     * @param integer $time UNIX timestamp value.
     */
    protected function migrateToTime($time)
    {
        $count = 0;
        $migrations = array_values($this->getMigrationHistory(null));
        while ($count < count($migrations) && $migrations[$count] > $time) {
            ++$count;
        }
        
        $this->migrateDownAll($count);
    }

    /**
     * Migrates to the certain version.
     * @param string $version name in the full format.
     * @return integer CLI exit code
     * @throws Exception if the provided version cannot be found.
     */
    protected function migrateToVersion($version)
    {
        $originalVersion = $version;

        // try migrate up
        $migrations = $this->getNewMigrations();
        foreach ($migrations as $i => $migration) {
            if (strpos($migration, $version . '_') === 0) {
                $this->migrateUpAll($i + 1);

                return self::EXIT_CODE_NORMAL;
            }
        }

        // try migrate down
        $migrations = array_keys($this->getMigrationHistory(null));
        foreach ($migrations as $i => $migration) {
            if (strpos($migration, $version . '_') === 0) {
                if ($i !== 0) {
                    $this->migrateDownAll($i);
                }

                return self::EXIT_CODE_NORMAL;
            }
        }

        throw new Exception("Unable to find the version '$originalVersion'.");
    }

    /**
     * Returns the migrations that are not applied.
     * @return array list of new migrations
     */
    protected function getNewMigrations()
    {
        $applied = [];
        foreach ($this->getMigrationHistory(null) as $version => $time) {
            $applied[substr($version, 1, 13)] = true;
        }

        $migrations = [];
        $handle = opendir($this->migrationPath);
        while (($file = readdir($handle)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $path = $this->migrationPath . DIRECTORY_SEPARATOR . $file;
            if (preg_match('/^(m(\d{6}_\d{6})_.*?)\.php$/', $file, $matches) && !isset($applied[$matches[2]]) && is_file($path)) {
                $migrations[] = $matches[1];
            }
        }
        closedir($handle);
        sort($migrations);

        return $migrations;
    }

    /**
     * Returns the migration history.
     * @param integer $limit the maximum number of records in the history to be returned. `null` for "no limit".
     * @return array the migration history
     */
    abstract protected function getMigrationHistory($limit);

    /**
     * Adds new migration entry to the history.
     * @param string $version migration version name.
     */
    abstract protected function addMigrationHistory($version);

    /**
     * Removes existing migration from the history.
     * @param string $version migration version name.
     */
    abstract protected function removeMigrationHistory($version);
}
