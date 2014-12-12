<?php

// Application
class App
{
    // Singleton instance
    private static $instance;

    // Singleton accessor
    public static function Instance()
    {
        if (App::$instance == null)
        {
            App::$instance = new App();
        }

        return App::$instance;
    }

    // Constructor
    private function __construct()
    {
        // Attach auto loader
        spl_autoload_register('App::AutoLoad');
    }
    
    // Refresh all channels
    public function Refresh()
    {
        Database\Channel::RefreshAll();
    }

    // Run application
    public function Run()
    {
        // Build request object
        $request = new Request($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_GET, $_POST, $_FILES);

        // Start session
        session_start();

        try
        {
            // Process the request
            $this->ProcessRequest($request);
        }
        catch (Exception $e)
        {
            // handle the error
            $this->HandleError($e);
        }
    }

    // Process the request object
    private function ProcessRequest($request)
    {
        // Construct page file path
        $page_path = dirname(__FILE__).'/page/'.$request->page.'.php';

        // If file doesn't exist
        if (!file_exists($page_path))
        {
            throw new Error\Http404('page file does not exist: '.$page_path);
        }

        // Require the file
        require_once($page_path);

        // Construct page class
        $page_class = 'page\\'.$request->page;

        // If class doesn't exist
        if (!class_exists($page_class))
        {
            throw new Error\Http500('page class does not exist: '.$page_class);
        }

        // Construct page object
        $page_object = new $page_class();

        // Construct page view name
        $page_view = 'view_'.$request->view;

        // If page view method doesn't exist
        if (!method_exists($page_object, $page_view))
        {
            throw new Error\Http404('page view does not exist: '.$page_view);
        }

        // Authorise the user
        $page_object->Authorise($request);

        // Generate and show page
        $page_object->Show($request->page, $request->view, $page_object->$page_view($request));
    }

    // Handle error
    private function HandleError($exception)
    {
        // If error subclass
        if (is_subclass_of($exception, 'Error'))
        {
            // Auto generate error request
            $this->ProcessRequest($exception->GenerateRequest());
        }
        else
        {
            // Handle generic exception
            $this->ProcessRequest(new Request('GET', '/error/exception', array('exception' => $exception), array(), array()));
        }
    }

    // Autoload class
    public function AutoLoad($class_name)
    {
        // To lower
        $class_name = strtolower($class_name);
        // Underscores are folder paths
        $class_name = str_replace('_', '/', $class_name);
        // Namespaces are folder paths
        $class_name = str_replace('\\', '/', $class_name);
        // Build file name
        $class_file = $class_name.'.php';
        // Build file path
        $class_path = dirname(__FILE__).'/'.$class_file;
        // If file exists
        if (file_exists($class_path))
        {
            // Require file
            require_once($class_path);
        }
    }
}
