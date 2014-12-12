<?php

namespace Database;

// Database log
class Log extends \Database
{
    // Log a message
    private static function Message($type, $message)
    {
        // Get date time
        $date = date('Y-m-d H:i:s', time());
        // Construct query
        $query = 'INSERT INTO logs (type, date, message) VALUES ("'.\Db::Instance()->Escape($type).'", "'.\Db::Instance()->Escape($date).'","'.\Db::Instance()->Escape($message).'");';
        // Execute query
        \Db::Instance()->Query($query);
    }

    // Log an info message
    public static function Info($message)
    {
        Log::Message('info', $message);
    }

    // Log an error message
    public static function Error($message)
    {
        Log::Message('error', $message);
    }

    // Log a warning message
    public static function Warn($message)
    {
        Log::Message('warn', $message);
    }

    // Count log entries
    public static function Count($filter)
    {
        // If filter set
        if ($filter)
        {
            // Set filter query
            $filter = ' AND type = "'.\Db::Instance()->Escape($filter).'"';
        }
        else
        {
            // No filter
            $filter = '';
        }
        // Construct query
        $query = 'SELECT COUNT(logs.id) AS total FROM logs WHERE true'.$filter.' ORDER BY date DESC;';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        if ($res && $res->num_rows)
        {
            $row = $res->fetch_assoc();
            return $row['total'];
        }
        return 0;
    }

    // Get log entries
    public static function Get($filter, $page, $limit)
    {
        // If filter set
        if ($filter)
        {
            // Set filter query
            $filter = ' AND type = "'.\Db::Instance()->Escape($filter).'"';
        }
        else
        {
            // No filter
            $filter = '';
        }
        // Construct query
        $query = 'SELECT logs.* FROM logs WHERE true'.$filter.' ORDER BY date DESC LIMIT '.\Db::Instance()->Escape($limit).' OFFSET '.\Db::Instance()->Escape(($page-1)*$limit).';';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        $logs = array();
        if ($res && $res->num_rows)
        {
            // Construct logs
            while ($row = $res->fetch_assoc())
            {
                // Construct log
                $log = Log::Factory($row);
                $logs[] = $log;
            }
        }
        return $logs;
    }
}
