<?php

namespace Database;

// Database channel
class Channel extends \Database
{
    // Refresh all channels
    public static function RefreshAll()
    {
        // Log start
        Log::Info('Started Channel::RefreshAll()');

        // Get all channels
        $channels = Channel::Factory();

        // For each channel
        foreach ($channels as $channel)
        {
            try
            {
                // Refresh channel
                $channel->Refresh();
            }
            catch(Exception $e)
            {
                // Log channel name and error
                Log::Error('['.$channel->title.'] '.$e->getMessage());
            }
        }

        // Log finished
        Log::Info('Finished Channel::RefreshAll()');
    }

    // Get the last update datetime
    public static function GetLastUpdated()
    {
        // Construct query
        $query = 'SELECT updated FROM channels ORDER BY updated DESC LIMIT 1;';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Get results
        if ($res && $res->num_rows)
        {
            $row = $res->fetch_assoc();
            return $row['updated'];
        }

        // Return null
        return null;
    }

    // Get items
    public function GetItems()
    {
        // Construct query
        $query = 'SELECT * FROM items WHERE channel_id = '.\Db::Instance()->Escape($this->id).';';
        // Execute query
        $res = \Db::Instance()->Query($query);
        $items = array();
        // Get results
        if ($res && $res->num_rows)
        {
            // Fetch rows
            while ($row = $res->fetch_assoc())
            {
                // Build item
                $item = Item::Factory($row);
                $items[] = $item;
            }
        }

        // Return items
        return $items;
    }

    // Refresh channel
    public function Refresh()
    {
        // Fetch feed object
        $feed = Channel::FetchData($this->url);

        if ($feed)
        {
            // Add items from feed
            $this->AddItems($feed);
        }
    }

    // Add items from feed
    public function AddItems(\Xml\IFeed $feed)
    {
        // If feed is not null
        if ($feed)
        {
            // For each entry
            foreach ($feed->GetEntries() as $entry)
            {
                // Add item
                $this->AddItem($entry);
            }

            // Coonstruct query
            $query = 'UPDATE channels SET updated = NOW() WHERE id = '.\Db::Instance()->Escape($this->id).';';
            // Execute query
            \Db::Instance()->Query($query);
        }
    }

    // Add item
    public function AddItem(\Xml\IEntry $entry)
    {
        // Turn off auto commit
        \Db::Instance()->AutoCommit(false);

        // Check guid is in db items
        $query = 'SELECT id FROM items WHERE channel_id = '.\Db::Instance()->Escape($this->id).' AND guid = "'.\Db::Instance()->Escape($entry->GetGuid()).'";';
        $res = \Db::Instance()->Query($query);
        // Check result
        $item_id = null;
        if ($res && $res->num_rows)
        {
            // Item exists
            $res = $res->fetch_assoc();
            $item_id = (int)$res['id'];
        }
        else
        {
            // Create item
            $query = 'INSERT INTO items (channel_id, guid, title, description, content, pubdate, link) VALUES ('.\Db::Instance()->Escape($this->id).',"'.\Db::Instance()->Escape($entry->GetGuid()).'","'.\Db::Instance()->Escape($entry->GetTitle()).'","'.\Db::Instance()->Escape($entry->GetDescription()).'","'.\Db::Instance()->Escape($entry->GetContent()).'","'.\Db::Instance()->Escape($entry->GetPubDate()).'","'.\Db::Instance()->Escape($entry->GetLink()).'")';
            \Db::Instance()->Query($query);

            // Get last insert id
            $item_id = \Db::Instance()->InsertId();

            // Update subscriptions
            $query = 'INSERT INTO users_items (user_id, item_id) SELECT users_channels.user_id AS user_id, items.id AS item_id FROM users_channels JOIN items ON users_channels.channel_id = items.channel_id LEFT JOIN users_items ON items.id = users_items.item_id WHERE users_items.item_id IS NULL;';
            \Db::Instance()->Query($query);
        }

        // Commit transaction
        \Db::Instance()->Commit();

        // Turn on auto commit
        \Db::Instance()->AutoCommit(true);

        // Return new item id - should this return item object?
        return $item_id;
    }

    // Fetch feed data
    public static function FetchData($url)
    {
        // Use internal errors
        libxml_use_internal_errors(true);

        try
        {
            // Construct object
            $xmlString = file_get_contents($url);
            if (!$xmlString)
            {
                throw new \Exception("Failed to fetch $url");
            }
            $xml = new \SimpleXmlElement($xmlString, LIBXML_NOWARNING, false);
            //$xml = new \SimpleXmlElement($url, LIBXML_NOWARNING, true);
        }
        catch (\Exception $e)
        {
            \Database\Log::Error($e->getMessage());
            return null;
        }

        // Not failed
        $failed = false;

        // Check for errors
        foreach (libxml_get_errors() as $error)
        {
            // Build message
            $message = "[$error->level : $error->code] [$error->line : $error->column] $error->message";

            // Check error level
            switch ($error->level)
            {
                case LIBXML_ERR_WARNING:
                    $failed = true;
                    // Log warning
                    \Database\Log::Warn($message);
                    break;
                case LIBXML_ERR_ERROR:
                case LIBXML_ERR_FATAL:
                    $failed = true;
                    // Log error
                    \Database\Log::Error($message);
                    break;
            }
        }

        // Clear errors
        libxml_clear_errors();

        // If failed
        if ($failed)
        {
            throw new Exception('Failed to parse xml');
        }

        // return new feed object
        return \Xml\Feed::FeedFactory($xml);
    }

    // Add new channel from feed data
    public static function Add($url, \Xml\IFeed $feed)
    {
        // Turn off auto commit
        \Db::Instance()->AutoCommit(false);

        // Check url is in db
        $query = 'SELECT id FROM channels WHERE url = "'.\Db::Instance()->Escape($url).'";';
        $res = \Db::Instance()->Query($query);
        // Check result
        $channel_id = null;
        if ($res && $res->num_rows)
        {
            // Item exists
            $res = $res->fetch_assoc();
            $channel_id = (int)$res['id'];
        }
        else
        {
            // Item does not exist
            $query = 'INSERT INTO channels (url, title, description) VALUES ("'.\Db::Instance()->Escape($url).'","'.\Db::Instance()->Escape($feed->GetTitle()).'","'.\Db::Instance()->Escape($feed->GetDescription()).'")';
            \Db::Instance()->Query($query);

            // Get last insert id
            $channel_id = \Db::Instance()->InsertId();
        }

        // Commit transaction
        \Db::Instance()->Commit();

        // Turn on auto commit
        \Db::Instance()->AutoCommit(true);

        // Return channel object
        return Channel::Factory($channel_id);
    }
}
