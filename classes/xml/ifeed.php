<?php

namespace Xml;

// Feed interface
interface IFeed
{
    // Construct
    public function __construct(\SimpleXMLElement $xml);

    // Get title
    public function GetTitle();

    // Get description
    public function GetDescription();

    // Get entries
    public function GetEntries();
}
