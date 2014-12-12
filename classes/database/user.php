<?php

namespace Database;

// Database user
class User extends \Database
{
    // Check user has role
    public function HasRole($required_role)
    {
        // For each role
        foreach ($this->GetRoles() as $role)
        {
            // Check for required role
            if ($role->name == $required_role)
            {
                // Authorised
                return true;
            }
        }
        // Not authorised
        return false;
    }

    // Get user roles
    public function GetRoles()
    {
        // Construct query
        $query = 'SELECT roles.* FROM users_roles JOIN roles ON users_roles.role_id = roles.id WHERE user_id = '.\Db::Instance()->Escape($this->id).';';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        $roles = array();
        if ($res && $res->num_rows)
        {
            // Construct roles
            while($row = $res->fetch_assoc())
            {
                // Construct role
                $roles[] = Role::Factory($row);
            }
        }
        return $roles;
    }

    // Get gravatar url
    public function GetGravatar($size = null)
    {
        return 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($this->email))).($size?'?s='.$size:'');
    }

    // Get channels
    public function GetChannels()
    {
        // Construct query
        $query = 'SELECT channels.* FROM channels JOIN users_channels ON users_channels.channel_id = channels.id WHERE user_id = '.\Db::Instance()->Escape($this->id).';';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        $channels = array();
        if ($res && $res->num_rows)
        {
            // Construct channels
            while ($row = $res->fetch_assoc())
            {
                // Construct channel
                $channel = Channel::Factory($row);
                // Construct query
                $query = 'SELECT COUNT(users_items.item_id) AS total FROM users_items JOIN items ON users_items.item_id = items.id WHERE user_id = '.\Db::Instance()->Escape($this->id).' AND items.channel_id = '.\Db::Instance()->Escape($channel->id).' AND users_items.unread = true;';
                // Execute query
                $total = \Db::Instance()->Query($query)->fetch_assoc();
                // Assign unread count
                $channel->unread = $total['total'];
                $channels[] = $channel;
            }
        }
        return $channels;
    }

    // Get channel
    public function GetChannel($channel_id)
    {
        // Construct query
        $query = 'SELECT channels.* FROM channels JOIN users_channels ON users_channels.channel_id = channels.id WHERE user_id = '.\Db::Instance()->Escape($this->id).' AND id = '.\Db::Instance()->Escape($channel_id).';';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        if ($res && $res->num_rows)
        {
            // Construct channel
            $row = $res->fetch_assoc();
            return Channel::Factory($row);
        }
        return null;
    }

    // Get setting
    public function GetSetting($name)
    {
        // Construct query
        $query = 'SELECT settings.* FROM settings WHERE user_id = '.\Db::Instance()->Escape($this->id).' AND name = "'.\Db::Instance()->Escape($name).'";';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        if ($res && $res->num_rows)
        {
            // Construct setting
            $row = $res->fetch_assoc();
            return Setting::Factory($row);
        }
        return null;
    }

    // Get item
    public function GetItem($item_id)
    {
        // Construct query
        $query = 'SELECT items.*, users_items.unread, users_items.starred FROM items JOIN users_items ON items.id = users_items.item_id WHERE user_id = '.\Db::Instance()->Escape($this->id).' AND item_id = '.\Db::Instance()->Escape($item_id).';';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        if ($res && $res->num_rows)
        {
            // Construct item
            $row = $res->fetch_assoc();
            return Item::Factory($row);
        }
        // No item
        return null;
    }

    // Unsubscribe
    public function Unsubscribe($channel_id)
    {
        // Turn off autocommit
        \Db::Instance()->AutoCommit(false);

        // Construct query
        $query = 'DELETE FROM users_channels WHERE user_id = '.\Db::Instance()->Escape($this->id).' AND channel_id = "'.\Db::Instance()->Escape($channel_id).'";';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        if ($res && $res->num_rows)
        {
            // Construct query
            $query = 'DELETE FROM users_items WHERE item_id IN (SELECT users_items.id FROM users_items JOIN items ON users_items.item_id = items.item_id WHERE user_id = '.\Db::Instance()->Escape($this->id).' AND channel_id = '.\Db::Instance()->Escape($channel_id).');';
            // Execute query
            $res = \Db::Instance()->Query($query);
        }

        // Commit transaction
        \Db::Instance()->Commit();

        // Turn on auto commit
        \Db::Instance()->AutoCommit(true);
    }

    // Count starred items
    public function CountStarredItems()
    {
        // Construct query
        $query = 'SELECT COUNT(items.id) AS total FROM items JOIN users_items ON items.id = users_items.item_id WHERE user_id = '.\Db::Instance()->Escape($this->id).' AND users_items.starred = true;';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        if ($res && $res->num_rows)
        {
            $row = $res->fetch_assoc();
            return $row['total'];
        }
        return 0;
    }

    // Get starred items
    public function GetStarredItems($page, $limit)
    {
        // Construct query
        $query = 'SELECT items.*, users_items.unread, users_items.starred FROM items JOIN users_items ON items.id = users_items.item_id WHERE user_id = '.\Db::Instance()->Escape($this->id).' AND starred = true ORDER BY pubdate DESC LIMIT '.\Db::Instance()->Escape($limit).' OFFSET '.\Db::Instance()->Escape(($page-1)*$limit).';';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        $items = array();
        if ($res && $res->num_rows)
        {
            // Construct items
            while ($row = $res->fetch_assoc())
            {
                // Construct item
                $items[] = Item::Factory($row);
            }
        }
        return $items;
    }

    // Set item unread state
    public function SetItemUnread($item_id, $unread)
    {
        // Construct query
        $query = 'UPDATE users_items SET unread = "'.\Db::Instance()->Escape($unread).'" WHERE user_id = '.\Db::Instance()->Escape($this->id).' AND item_id = '.\Db::Instance()->Escape($item_id).';';
        // Execute query
        return \Db::Instance()->Query($query);
    }

    // Set item starred state
    public function SetItemStarred($item_id, $starred)
    {
        // Construct query
        $query = 'UPDATE users_items SET starred = "'.\Db::Instance()->Escape($starred).'" WHERE user_id = '.\Db::Instance()->Escape($this->id).' AND item_id = '.\Db::Instance()->Escape($item_id).';';
        // Execute query
        return \Db::Instance()->Query($query);
    }

    // Get channels for update
    public function GetChannelsForUpdate()
    {
        // Construct query
        $query = 'SELECT channels.* FROM channels JOIN users_channels ON users_channels.channel_id = channels.id WHERE user_id = '.\Db::Instance()->Escape($this->id).' AND updated < NOW() - INTERVAL 1 HOUR;';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        $channels = array();
        if ($res && $res->num_rows)
        {
            // Construct channels
            while ($row = $res->fetch_assoc())
            {
                // Construct channel
                $channels[] = Channel::Factory($row);
            }
        }
        return $channels;
    }

    // Get prev channel item
    public function GetChannelItemPrev(Item $item, $all)
    {
        // If not all filter
        if (!$all)
        {
            // Set filter for unread
            $all = ' AND users_items.unread = true';
        }
        else
        {
            // No filter
            $all = '';
        }
        // Construct query
        $query = 'SELECT items.* FROM items JOIN users_items ON items.id = users_items.item_id WHERE user_id = '.\Db::Instance()->Escape($this->id).' AND items.id != '.\Db::Instance()->Escape($item->id).' AND items.channel_id = '.\Db::Instance()->Escape($item->channel_id).$all.' AND pubdate <= "'.\Db::Instance()->Escape($item->pubdate).'" ORDER BY pubdate DESC LIMIT 1;';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        if ($res && $res->num_rows)
        {
            // Construct item
            $row = $res->fetch_assoc();
            return Item::Factory($row);
        }
        // No item
        return null;
    }

    // Get next channel item
    public function GetChannelItemNext(Item $item, $all)
    {
        // If not all filter
        if (!$all)
        {
            // Set filter for unread
            $all = ' AND users_items.unread = true';
        }
        else
        {
            // No filter
            $all = '';
        }
        // Construct query
        $query = 'SELECT items.id FROM items JOIN users_items ON items.id = users_items.item_id WHERE user_id = '.\Db::Instance()->Escape($this->id).$all.' AND items.id != '.\Db::Instance()->Escape($item->id).' AND items.channel_id = '.\Db::Instance()->Escape($item->channel_id).' AND pubdate >= "'.\Db::Instance()->Escape($item->pubdate).'" ORDER BY pubdate ASC LIMIT 1;';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        if ($res && $res->num_rows)
        {
            // Construct item
            $row = $res->fetch_assoc();
            return Item::Factory($row);
        }
        // No item
        return null;
    }

    // Refresh channels
    public function RefreshChannels()
    {
        // For each channel
        foreach ($this->GetChannelsForUpdate() as $channel)
        {
            // Refresh channel
            $channel->Refresh();
        }
    }

    // Count channel items
    public function CountChannelItems($channel_id, $all)
    {
        // If not all filter
        if (!$all)
        {
            // Set filter for unread
            $all = ' AND users_items.unread = true';
        }
        else
        {
            // No filter
            $all = '';
        }
        // Cosntruct query
        $query = 'SELECT COUNT(items.id) AS total FROM items JOIN users_items ON items.id = users_items.item_id WHERE user_id = '.\Db::Instance()->Escape($this->id).' AND channel_id = '.\Db::Instance()->Escape($channel_id).$all.';';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        if ($res && $res->num_rows)
        {
            $row = $res->fetch_assoc();
            return $row['total'];
        }
        return 0;
    }

    // Get channel items
    public function GetChannelItems($channel_id, $all, $page, $limit)
    {
        // If not all filter
        if (!$all)
        {
            // Set filter for unread
            $all = ' AND users_items.unread = true';
        }
        else
        {
            // No filter
            $all = '';
        }
        // Construct query
        $query = 'SELECT items.*, users_items.unread, users_items.starred FROM items JOIN users_items ON items.id = users_items.item_id WHERE user_id = '.\Db::Instance()->Escape($this->id).' AND channel_id = '.\Db::Instance()->Escape($channel_id).$all.' ORDER BY pubdate DESC LIMIT '.\Db::Instance()->Escape($limit).' OFFSET '.\Db::Instance()->Escape(($page-1)*$limit).';';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        $items = array();
        if ($res && $res->num_rows)
        {
            // Cosntruct items
            while ($row = $res->fetch_assoc())
            {
                // Construct item
                $items[] = Item::Factory($row);
            }
        }
        return $items;
    }

    // Subscribe to a feed url
    public function Subscribe($url)
    {
        // Fetch url data
        $data = Channel::FetchData($url);
        // Attempt to add channel
        $channel = Channel::Add($url, $data);
        // If channel is available
        if ($channel)
        {
            // Subscribe user to channel
            $query = 'INSERT INTO users_channels (user_id, channel_id) VALUES ('.\Db::Instance()->Escape($this->id).','.\Db::Instance()->Escape($channel->id).');';
            // If successful
            if (\Db::Instance()->Query($query))
            {
                // REfresh channel
                $channel->Refresh();
                return true;
            }
        }
        return false;
    }

    // Count users
    public static function Count()
    {
        // Construct query
        $query = 'SELECT COUNT(users.id) AS total FROM users ORDER BY username DESC;';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        if ($res && $res->num_rows)
        {
            $row = $res->fetch_assoc();
            return $row['total'];
        }
        return 0;
    }

    // Get users
    public static function Get($page, $limit)
    {
        // Construct query
        $query = 'SELECT users.* FROM users ORDER BY username DESC LIMIT '.\Db::Instance()->Escape($limit).' OFFSET '.\Db::Instance()->Escape(($page-1)*$limit).';';
        // Execute query
        $res = \Db::Instance()->Query($query);
        // Check result
        $users = array();
        if ($res && $res->num_rows)
        {
            // Construct users
            while ($row = $res->fetch_assoc())
            {
                // Construct user
                $users[] = User::Factory($row);
            }
        }
        return $users;
    }
}
