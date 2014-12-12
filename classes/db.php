<?php

// Db
class Db
{
    // Singleton instance
    private static $instance = null;

    // Get singleton instance
    public static function Instance()
    {
        // If instance is null
        if (Db::$instance == null)
        {
            // Construct instance
            Db::$instance = new Db();
        }

        // Return instance
        return Db::$instance;
    }

    // Mysqli object
    private $mysqli = null;

    // Construct
    private function __construct()
    {
        // Construct Mysqli object
        $this->mysqli = new MySqli(
            Config::Get('db', 'hostname'),
            Config::Get('db', 'username'),
            Config::Get('db', 'password'),
            Config::Get('db', 'database')
        );
    }

    // Execute Query
    public function Query($query)
    {
        // Execute query
        $res = $this->mysqli->query($query);
        // If error
        if ($this->mysqli->errno)
        {
            // Throw exception
            throw new Exception($this->mysqli->error);
        }
        // Return result
        return $res;
    }

    // Escape string
    public function Escape($string)
    {
        // Return escaped string
        return $this->mysqli->real_escape_string($string);
    }

    // Set auto commit
    public function AutoCommit($bool)
    {
        $this->mysqli->autocommit($bool);
    }

    // Commit transaction
    public function Commit()
    {
        $this->mysqli->commit();
    }

    // Get last insert id
    public function InsertId()
    {
        return $this->mysqli->insert_id;
    }
}
