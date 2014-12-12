<?php

namespace Xml;

// Xml import
class Import extends \Xml
{
    // Import factory
    public static function ImportFactory(\SimpleXMLElement $xml)
    {
        if ($xml->getName() != 'opml')
        {
            throw new Exception('not an opml file');
        }
        return new Import($xml);
    }

    // Construct
    protected function __construct(\SimpleXMLElement $xml)
    {
        parent::__construct($xml);
    }

    // Get feed urls
    public function GetFeedUrls()
    {
        // Create urls array
        $urls = array();

        // Get urls from xml
        foreach ($this->xml->xpath('body/outline/outline') as $url)
        {
            // For each feed url
            foreach ($url->attributes() as $key => $val)
            {
                // Get xml url
                if ($key == 'xmlUrl')
                {
                    $urls[] = (string)$val;
                    break;
                }
            }
        }

        // Return urls
        return $urls;
    }
}
