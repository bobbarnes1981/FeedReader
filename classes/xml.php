<?php

// Xml
abstract class Xml
{
    // Xml data
    protected $xml;

    // Construct
    protected function __construct(SimpleXmlElement $xml)
    {
        $this->xml = $xml;
    }
}
