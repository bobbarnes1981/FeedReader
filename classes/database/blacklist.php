<?php

namespace Database;

// Database blacklist
class Blacklist extends \Database
{
    // Add to blacklist
    public static function Add($source, $username, $password)
    {
        // Log blacklisted
        Log::Warn('Blacklisted: '.$source);
        // Get date time
        $date = date('Y-m-d H:i:s', time());
        // Construct query
        $query = 'INSERT INTO blacklist (source, username, password, attempted) VALUES ("'.\Db::Instance()->Escape($source).'", "'.\Db::Instance()->Escape($username).'", "'.\Db::Instance()->Escape($password).'", "'.\Db::Instance()->Escape($date).'");';
        // Execute query
        \Db::Instance()->Query($query);
    }

    // Check if source is blacklisted
    public static function Blacklisted($source, $username)
    {
        // Construct query
        $query = 'SELECT COUNT(blacklist.id) FROM blacklist WHERE source = "'.\Db::Instance()->Escape($source).'" AND username = "'.\Db::Instance()->Escape($username).'" AND DATE_SUB(NOW(),INTERVAL '.\Config::Get('blacklist', 'hours').' HOUR) <= attempted;';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        if ($res && $res->num_rows)
        {
            $row = $res->fetch_assoc();
            return $row['total'] >= \Config::Get('blacklist', 'count');
        }
        return false;
    }
}
