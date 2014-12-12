<?php

namespace Page;

// Page channel
class Channel extends \Page
{
    // Required user roles
    protected $secure =array('view' => 'user');

    // View view
    public function View_View($request)
    {
        $user = \Auth::LoggedIn();

        if (isset($request->get['all']))
        {
            \Session::Set('all', ($request->get['all']=='true'?true:false));
        }

        // Parameters
        $id = (isset($request->get['id'])?$request->get['id']:0);
        $page = (isset($request->get['page'])?$request->get['page']:1);

        // Channel
        $channel = $user->GetChannel($id);
        if (!$channel)
        {
            throw new Exception('Invalid channel id');
        }

        // Pagination
        $per_page = 20;
        $items_count = $user->CountChannelItems($id, \Session::Get('all', false));
        $items = $user->GetChannelItems($id, \Session::Get('all', false), $page, $per_page);
        $max = (int)($items_count / $per_page);
        if ($max < ($items_count / $per_page))
        {
            $max = $max + 1;
        }
        $pagination = new \Template('templates/pagination.php',
            array(
                'url' => '/channel/view?id='.$channel->id.'&page=',
                'page' => $page,
                'max' => $max,
            )
        );

        return array('channel' => $channel, 'items' => $items, 'pagination' => $pagination, 'page' => $page);
    }
}
