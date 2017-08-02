<?php
/**
 * [Vella Module]
 * Name: Database Class
 * Author: Precious Opusunju
 */

namespace Vella\Module;

class Database {
    private $host, $user, $pass, $db, $connection;
    public function __construct()
    {
        $this->host = '';
        $this->user = '';
        $this->pass = '';
        $this->db   = '';

    }

    public function connect()
    {
        $this->connection = new \mysqli($this->host, $this->user, $this->pass, $this->db);

        if(!$this->connection)
        {
            die($this->connection->error);
        }
    }

    public function insert($table, $array)
    {

    }

    public function update($table, $array)
    {

    }

    public function delete($table, $array)
    {

    }

    public function where($column, $value)
    {

    }
}

$db = new Database();
$db->connect();