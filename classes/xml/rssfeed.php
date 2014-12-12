<?php

namespace Xml;

// Rss feed
class RssFeed extends \Xml implements IFeed
{
    // Construct
    public function __construct(\SimpleXMLElement $xml)
    {
        parent::__construct($xml);
    }

    // Get title
    public function GetTitle()
    {
        return $this->xml->channel->title;
    }

    // Get description
    public function GetDescription()
    {
        return $this->xml->channel->description;
    }

    // Get entries
    public function GetEntries()
    {
        return Entry::EntryFactory($this->xml);
    }
}
