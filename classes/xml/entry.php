<?php

namespace Xml;

// Entry
abstract class Entry
{
    // Entry factory
    public static function EntryFactory(\SimpleXMLElement $xml)
    {
        // Check if RSS or ATOM
        switch ($xml->getName())
        {
            case 'feed':
                // ATOM
                $entry_class = '\\Xml\\AtomEntry';
                $list = $xml->children('http://www.w3.org/2005/Atom')->entry;
                break;
            case 'rss':
                // RSS
                $entry_class = '\\Xml\\RssEntry';
                $list = $xml->xpath('channel/item');
                break;
            default:
                // Unknown
                throw new Exception('unrecognised feed type');
        }

        // Build an array of entries
        $entries = array();
        foreach ($list as $entry)
        {
            // Build entry
            $entries[] = new $entry_class($entry);
        }

        // Return entries
        return $entries;
    }
}
