<?php

// Config
class Config
{
    // Singleton instance
    private static $instance = null;

    // Get singleton instance
    public static function Instance()
    {
        // If instance is null
        if (Config::$instance == null)
        {
            // Construct instance
            Config::$instance = new Config();
        }

        // Return instance
        return Config::$instance;
    }

    // Construct
    private function __construct()
    {
        // Require config file
        require_once(dirname(__FILE__).'/../config.php');

        // Load configs
        $this->configs = $configs;
    }

    // Local configs array
    private $configs = array();

    // Get config
    public static function Get($section, $config)
    {
        // Check section exists
        if (array_key_exists($section, Config::Instance()->configs))
        {
            // Check config exists
            if (array_key_exists($config, Config::Instance()->configs[$section]))
            {
                // Return config
                return Config::Instance()->configs[$section][$config];
            }
        }

        // Config not found
        return null;
    }
}
