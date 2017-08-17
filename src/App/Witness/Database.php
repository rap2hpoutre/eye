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
        if (is_array(config('eyewitness.database_connections'))) {
            $connections = config('eyewitness.database_connections');
        } else {
            $connections = [config('database.default')];
        }

        foreach ($connections as $connection) {
            try {
                $data[$connection]['db_status'] = $this->checkDatabaseStatus($connection);
                $data[$connection]['db_size'] = $this->checkDatabaseSize($connection);
            } catch (Exception $e) {
                $data[$connection]['db_status'] = false;
                $data[$connection]['db_size'] = -1;
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
     * Check the size of the database.
     *
     * @param  string  $connection
     * @return bool
     */
    protected function checkDatabaseSize($connection)
    {
        switch(DB::connection($connection)->getDriverName()) {
            case 'mysql':
                return $this->checkMySqlDatabaseSize($connection);
            case 'sqlite':
                return $this->checkSqLiteDatabaseSize($connection);
            case 'pgsql':
                return $this->checkPostgresDatabaseSize($connection);
        }

        return -1;
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
}
