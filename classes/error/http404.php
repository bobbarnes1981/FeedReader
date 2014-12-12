<?php

namespace Error;

// Http 404
class Http404 extends \Error
{
    // Construct
    public function __construct($message)
    {
        parent::__construct($message);
    }

    // Generate request
    public function GenerateRequest()
    {
        return new \Request('GET', '/error/http404', array(), array(), array());
    }
}
