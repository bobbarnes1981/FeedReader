<?php

namespace Xml;

// Atom entry
class AtomEntry extends \Xml implements IEntry
{
    // Cosntruct
    public function __construct(\SimpleXMLElement $xml)
    {
        parent::__construct($xml);
    }

    // Get guid
    public function GetGuid()
    {
        return $this->xml->id;
    }

    // Get title
    public function GetTitle()
    {
        return $this->xml->title;
    }

    // Get description
    public function GetDescription()
    {
        return $this->xml->summary;
    }

    // Get content
    public function GetContent()
    {
        return '';
    }

    // Get pubdate
    public function GetPubDate()
    {
        return date('Y-m-d H:i:s', strtotime($this->xml->updated));
    }

    // Get link
    public function GetLink()
    {
        return $this->xml->link;
    }

}
