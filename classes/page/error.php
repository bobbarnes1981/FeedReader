<?php

namespace Page;

// Page error
class Error extends \Page
{
    // View exception
    public function View_Exception($request)
    {
        return array('exception' => $request->get['exception']);
    }

    // View 404
    public function View_Http404($request)
    {
        header('HTTP/1.0 404 Not Found');
        return array();
    }

    // View 500
    public function View_Http500($request)
    {
        header('HTTP/1.0 500 Internal Server Error');
        return array('message' => $request->get['message']);
    }
}
