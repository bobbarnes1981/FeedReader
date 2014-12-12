<?php

// Error
abstract class Error extends Exception
{
    // Constructor
    public function __construct($message)
    {
        parent::__construct($message);
    }

    // Generate request object
    public abstract function GenerateRequest();
}
