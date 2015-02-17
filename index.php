<?php

// Include app
require_once('classes/app.php');

// If running from cli
if (php_sapi_name() == 'cli')
{
    // Refresh all channels
    App::Instance()->Cli($argv);
}
else
{
    // Instantiate and run app
    App::Instance()->Run();
}
