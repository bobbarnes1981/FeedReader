<?php

namespace Xml;

// Atom feed
class AtomFeed extends \Xml implements IFeed
{
    // Construct
    public function __construct(\SimpleXMLElement $xml)
    {
        parent::__construct($xml);
    }

    // Get title
    public function GetTitle()
    {
        return $this->xml->title;
    }

    // Get description
    public function GetDescription()
    {
        return $this->xml->subtitle;
    }

    // Get entries
    public function GetEntries()
    {
        return Entry::EntryFactory($this->xml);
    }
}
