<?php

namespace Xml;

// Rss entry
class RssEntry extends \Xml implements IEntry
{
    // Construct
    public function __construct(\SimpleXMLElement $xml)
    {
        parent::__construct($xml);
    }

    // Get guid
    public function GetGuid()
    {
        return $this->xml->guid;
    }

    // Get title
    public function GetTitle()
    {
        return $this->xml->title;
    }

    // Get description
    public function GetDescription()
    {
        return $this->xml->description;
    }

    // Get content
    public function GetContent()
    {
        return $this->xml->children('http://purl.org/rss/1.0/modules/content/')->encoded;
    }

    // Get pubdate
    public function GetPubDate()
    {
        return date('Y-m-d H:i:s', strtotime($this->xml->pubDate));
    }

    // Get link
    public function GetLink()
    {
        return $this->xml->link;
    }
}
