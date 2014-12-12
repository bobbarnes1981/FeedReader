<?php

namespace Page;

// Page subscription
class Subscription extends \Page
{
    // Required user roles
    protected $secure =array('add' => 'user', 'del' => 'user', 'import' => 'user');

    // View add
    public function View_Add($request)
    {
        $user = \Auth::LoggedIn();

        // Parameters
        $url = (isset($request->post['url'])?$request->post['url']:null);

        // If posted
        if ($request->method == 'POST')
        {
            // Attempt to subscribe
            if ($user->Subscribe($url))
            {
                // Success
                header('Location: /home/view');
            }
            else
            {
                // Failed
                $this->alerts['danger'][] = 'Failed to subscribe';
            }
        }

        return array('url' => $url);
    }

    // View del
    public function View_Del($request)
    {
        $user = \Auth::LoggedIn();

        // Parameters
        $id = (isset($request->get['id'])?$request->get['id']:0);

        // Channel
        $channel = $user->GetChannel($id);
        if (!$channel)
        {
            throw new Exception('Invalid channel id');
        }

        // If posted
        if ($request->method == 'POST')
        {
            $unsubscribe = ($request->post['unsubscribe'] == 'true'?true:false);
            // If unsubscribe
            if ($unsubscribe)
            {
                // Attempt unsubscribe
                $user->Unsubscribe($channel->id);
            }

            // Return home
            header('Location: /home/view');
        }

        return array('channel' => $channel);
    }

    // View import
    public function View_Import($request)
    {
        $user = \Auth::LoggedIn();

        // If posted
        if ($request->method == 'POST')
        {
            // If no file errors
            if ($request->files['file']['error'] == 0)
            {
                // Get import data
                $data = Import::ImportFactory(new SimpleXMLElement(file_get_contents($request->files['file']['tmp_name'])));

                // For each url
                foreach ($data->GetFeedUrls() as $url)
                {
                    // Subscribe user to url
                    $user->Subscribe($url);
                }

                // Return home
                header('Location: /home/view');
            }
            else
            {
                // Report errors
                $this->alerts['danger'][] = $request->files['file']['error'];
            }
        }

        return array();
    }
}
