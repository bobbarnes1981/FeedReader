<?php

// Session
class Session
{
    // Set session value
    public static function Set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    // Get session value
    public static function Get($key, $default = null)
    {
        // Get session variable
        if (isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        }

        return $default;
    }

    // Del session value
    public static function Del($key)
    {
        // Unset session variable
        if (isset($_SESSION[$key]))
        {
            unset($_SESSION[$key]);
        }
    }
}
