<?php

namespace App\Traits;

trait ClickhouseMigrations
{
    public function fromMysqlTable(string $tableName, string $query = null)
    {
        $mysqlHOST = config('database.connections.mysql.host');
        $mysqlPORT = config('database.connections.mysql.port');
        $mysqlDATABASE = config('database.connections.mysql.database');
        $mysqlUSERNAME = config('database.connections.mysql.username');
        $mysqlPASSWORD = config('database.connections.mysql.password');


        $sql = "HOST '{$mysqlHOST}'
                PORT {$mysqlPORT}
                USER '{$mysqlUSERNAME}'
                PASSWORD '{$mysqlPASSWORD}'
                DB '{$mysqlDATABASE}'
                TABLE '{$tableName}'";

        if ($query) {
            $sql .= " QUERY '{$query}'";
        }

        return $sql;
    }
}
