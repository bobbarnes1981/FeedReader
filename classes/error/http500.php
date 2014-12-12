<?php

namespace Error;

// Http 500
class Http500 extends \Error
{
    // Cosntruct
    public function __construct($message)
    {
        parent::__construct($message);
    }

    // Generate request
    public function GenerateRequest()
    {
        return  new \Request('GET', '/error/http500', array('message' => $this->message), array(), array());
    }
}
