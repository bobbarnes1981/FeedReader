<?php

namespace Page;

// Page home
class Home extends \Page
{
    // Required user roles
    protected $secure =array('view' => 'user');

    // View view
    public function View_View($request)
    {
        $user = \Auth::LoggedIn();

        // Channels
        $channels = $user->GetChannels();

        // Alerts
        $this->alerts['info'][] = 'Last updated: '.\Database\Channel::GetLastUpdated();

        return array('channels' => $channels);
    }
}
