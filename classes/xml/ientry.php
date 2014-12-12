<?php

namespace Xml;

// Entry interface
interface IEntry
{
    // Cosntruct
    public function __construct(\SimpleXMLElement $xml);

    // Get guid
    public function GetGuid();

    // Get title
    public function GetTitle();

    // Get description
    public function GetDescription();

    // Get content
    public function GetContent();

    // Get pubdate
    public function GetPubDate();

    // Get link
    public function GetLink();
}
