<?php

namespace Page;

// Page item
class Item extends \Page
{
    // Required user roles
    protected $secure =array('view' => 'user', 'state' => 'user', 'starred' => 'user');

    // View view
    public function View_View($request)
    {
        $user = \Auth::LoggedIn();

        // Parameters
        $id = (isset($request->get['id'])?$request->get['id']:0);

        // Item
        $item = $user->GetItem($id);
        if (!$item)
        {
            throw new Exception('Invalid item id');
        }

        // Channel
        $channel = $user->GetChannel($item->channel_id);

        // Pager
        $prev = $user->GetChannelItemPrev($item, \Session::Get('all', false));
        $next = $user->GetChannelItemNext($item, \Session::Get('all', false));
        $pager = new \Template('templates/pager.php',
            array(
                'url' => '/item/view?id=',
                'prev' => $prev,
                'next' => $next
            )
        );

        return array('channel' => $channel, 'item' => $item, 'pager' => $pager);
    }

    // View state
    public function View_State($request)
    {
        $user = \Auth::LoggedIn();

        $return = null;
        if (isset($request->get['return']))
        {
            $return = $request->get['return'];
        }

        // Parameters
        $id = (isset($request->get['id'])?$request->get['id']:0);

        // Load item
        $item = $user->GetItem($id);

        // Unread
        if (isset($request->get['unread']))
        {
            $unread = ($request->get['unread'] == 'true'?true:false);
            $user->SetItemUnread($id, $unread);
        }

        // Starred
        if (isset($request->get['starred']))
        {
            $starred = ($request->get['starred'] == 'true'?true:false);
            $user->SetItemStarred($id, $starred);
        }

        switch($return)
        {
            case 'item':
                $return = '/item/view?id='.$item->id;
                break;
            case 'channel':
                $return = '/channel/view?id='.$item->channel_id;
                break;
        }

        header('Location: '.$return);

        return array();
    }

    // View starred
    public function View_Starred($request)
    {
        $user = \Auth::LoggedIn();

        // Parameters
        $page = (isset($request->get['page'])?$request->get['page']:1);

        // Pagination
        $per_page = 20;
        $items_count = $user->CountStarredItems();
        $items = $user->GetStarredItems($page, $per_page);
        $max = (int)($items_count / $per_page);
        if ($max < ($items_count / $per_page))
        {
            $max = $max + 1;
        }
        $pagination = new \Template('templates/pagination.php',
            array(
                'url' => '/item/starred?page=',
                'page' => $page,
                'max' => $max
            )
        );

        return array('items' => $items, 'pagination' => $pagination);
    }
}
