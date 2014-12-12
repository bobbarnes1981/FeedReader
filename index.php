<?php

// Set timezone
date_default_timezone_set('Europe/London');

// Include app
require_once('classes/app.php');

// If running from cli
if (php_sapi_name() == 'cli')
{
    // Refresh all channels
    App::Instance()->Refresh();
}
else
{
    // Instantiate and run app
    App::Instance()->Run();
}
