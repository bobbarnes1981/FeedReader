<?php

namespace Xml;

// Feed
abstract class Feed 
{
    // Feed factory
    public static function FeedFactory(\SimpleXMLElement $xml)
    {
        // Check if RSS or ATOM
        switch ($xml->getName())
        {
            case 'feed':
                // ATOM
                return new AtomFeed($xml);
            case 'rss':
                // RSS
                return new RssFeed($xml);
            default:
                // Unknown
                throw new Exception('unrecognised feed type');
        }
    }
}
