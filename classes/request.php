<?php

// Request
class Request
{
    // Uri
    public $uri;

    // Get array
    public $get = array();
    // Post array
    public $post = array();
    // Files array
    public $files = array();

    // Page name
    public $page;
    // View name
    public $view;

    // Method (POST,GET)
    public $method;

    // Construct
    public function __construct($method, $uri, $get, $post, $files)
    {
        // Set uri
		$offset = strlen(Config::get('site', 'base_uri'))+1;
        $this->uri = substr($uri, $offset);

        // Set the method
        $this->method = $method;

        // Create empty route
        $route = null;

        // Strip the query string
        if (strpos($this->uri, '?') !== false)
        {
            $route = explode('?', $this->uri);
            $route = $route[0];
        }
        else
        {
            $route = $this->uri;
        }

	error_log($this->uri);

        // Copy the get array
        foreach ($get as $key => $val)
        {
            $this->get[$key] = $val;
        }

        // Copy the post array
        foreach ($post as $key => $val)
        {
            $this->post[$key] = $val;
        }

        // Copy the files array
        foreach ($files as $key => $val)
        {
            $this->files[$key] = $val;
        }

        // Convert the route to an array
        $route = explode('/',trim($route,'/'));
        if (count($route) == 1 && $route[0] == "")
        {
            $route = array();
        }

        // Set default values
        $this->page = 'home';
        $this->view = 'view';

        // If route has page
        if (count($route)>0)
        {
            // Set page
            $this->page = strtolower($route[0]);
        }
        // If route has view
        if (count($route)>1)
        {
            // Set view
            $this->view = strtolower($route[1]);
        }
    }
}
