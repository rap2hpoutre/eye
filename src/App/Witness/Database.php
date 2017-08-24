<?php

namespace Eyewitness\Eye\App\Witness;

use Illuminate\Support\Facades\DB;
use Exception;

class Database
{
    /**
     * Perform checks for each database.
     *
     * @return array
     */
    public function check()
    {
        $data = [];

        foreach ($this->getMonitoredDatabases() as $connection) {
            try {
                $data[] = ['connection' => $connection,
                           'driver' => $this->getDriverName($connection),
                           'status' => $this->checkDatabaseStatus($connection),
                           'size' => $this->getDatabaseSize($connection)];
            } catch (Exception $e) {
                $data[] = ['connection' => $connection,
                           'driver' => 'unknown',
                           'status' => false,
                           'size' => -1];
            }
        }

        return $data;
    }

    /**
     * Check if the database connection is working.
     *
     * @param  string  $connection
     * @return bool
     */
    protected function checkDatabaseStatus($connection)
    {
        try {
            DB::connection($connection)->getPdo();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Get the size of the database.
     *
     * @param  string  $connection
     * @return bool
     */
    protected function getDatabaseSize($connection)
    {
        switch($this->getDriverName($connection)) {
            case 'mysql':
                $size = $this->checkMySqlDatabaseSize($connection);
                break;
            case 'sqlite':
                $size = $this->checkSqLiteDatabaseSize($connection);
                break;
            case 'pgsql':
                $size = $this->checkPostgresDatabaseSize($connection);
                break;
            default:
                return -1;
        }

        return round($size/1024/1024, 2);
    }

    /**
     * Return the size of the mySql database.
     *
     * @param  string  $connection
     * @return int
     */
    protected function checkMySqlDatabaseSize($connection)
    {
        try {
            $result = (array) DB::connection($connection)
                                ->table('information_schema.TABLES')
                                ->select(DB::raw("sum( data_length + index_length ) AS size"))
                                ->where('table_schema', DB::connection($connection)->getConfig('database'))
                                ->first();

            return $result['size'];
        } catch (Exception $e) {
            return -1;
        }
    }

    /**
     * Return the size of the Sqlite database.
     *
     * @param  string  $connection
     * @return int
     */
    protected function checkSqLiteDatabaseSize($connection)
    {
        try {
            $path = realpath(DB::connection($connection)->getConfig('database'));
            if ($path !== false) {
                return filesize($path);
            }
        } catch (Exception $e) {
            //
        }

        return -1;
    }

    /**
     * Return the size of the Postgres database.
     *
     * @param  string  $connection
     * @return int
     */
    protected function checkPostgresDatabaseSize($connection)
    {
        try {
            $result = DB::connection($connection)->select(DB::raw("select pg_database_size('".DB::connection($connection)->getConfig('database')."')"));
            $result = (array) $result[0];
            return $result['pg_database_size'];
        } catch (Exception $e) {
            return -1;
        }
    }

    /**
     * Get the name of the database driver.
     *
     * @param  string  $connection
     * @return bool
     */
    protected function getDriverName($connection)
    {
        return DB::connection($connection)->getDriverName();
    }

    /**
     * Get the list of databases we are monitoring.
     *
     * @return array
     */
    protected function getMonitoredDatabases()
    {
        if (is_array(config('eyewitness.database_connections'))) {
            return config('eyewitness.database_connections');
        }

        return [config('database.default')];
    }
}
