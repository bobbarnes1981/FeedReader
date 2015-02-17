<?php

// Page
abstract class Page
{
    // Alerts
    protected $alerts = array('danger' => array(), 'success' => array(), 'info' => array());

    // Array of secure views and required user role name
    protected $secure = array();

    // Authorise user
    public function Authorise($request)
    {
        if (array_key_exists($request->view, $this->secure))
        {
            // Get logged in user
            $user = Auth::LoggedIn();

            // If user not logged in
            if ($user == null || !$user->HasRole($this->secure[$request->view]))
            {
                // Store target page
                Session::Set('target', $request->uri);
                // Redirect to account login
                header('Location: '.Url::get('account', 'login'));
            }
        }
    }

    // Show page
    public function Show($page_name, $view_name, $data)
    {
        // Construct view file path
        $view_path = 'templates/'.$page_name.'/'.$view_name.'.php';

        // Active navigation button id
        $active = '__'.$page_name.'__'.$view_name;

        // Specify left buttons
        $buttons_left = array(
            // Add home button
            array(
                'title' => 'Home',
                'id'    => '__home__view',
                'link'  => Url::get('home', 'view')
            ),
            // Add subscribe button
            array(
                'title' => 'Subscribe', 
                'id'    => '__subscription__add',
                'link'  => Url::get('subscription', 'add')
            ),
            // Add import button
            array(
                'title' => 'Import', 
                'id'    => '__subscription__import',
                'link'  => Url::get('subscription', 'import')
            ),
            // Add starred button
            array(
                'title' => 'Starred', 
                'id'    => '__item__starred',
                'link'  => Url::get('item', 'starred')
            ),
        );

        $buttons_right = array();

        // Get logged in user
        $user = Auth::LoggedIn();
        if ($user)
        {
            // If admin
            if ($user->HasRole('admin'))
            {
                // Add user button
                $buttons_left[] = array(
                    'title' => 'User',
                    'id'    => '__user__view',
                    'link'  => Url::get('user', 'view'),
                );
                // Add log button
                $buttons_left[] = array(
                    'title' => 'Log',
                    'id'    => '__log__view',
                    'link'  => Url::get('log', 'view'),
                );
            }
            // Add user buttons
            $buttons_right[] = array(
                // Add dropdown with username and gravatar
                'title' => $user->username . ' <img src="'.$user->GetGravatar(24).'" />', 
                'id'    => '',
                'link'  => array(
                    // Add account button
                    array(
                        'title' => 'Account', 
                        'id'    => '__account__view',
                        'link'  => Url::get('account', 'view')
                    ),
                    // Add settings button
                    array(
                        'title' => 'Settings', 
                        'id'    => '__settings__view',
                        'link'  => Url::get('settings', 'view')
                    ),
                    // Add logout button
                    array(
                        'title' => 'Logout', 
                        'id'    => '__account__logout',
                        'link'  => Url::get('account', 'logout')
                    ),
                )
            );
        }

        // Construct content template
        $content = new Template($view_path, $data);

        // Construct alerts template
        $alerts = new Template('templates/alerts.php',
            array(
                'alerts'        => $this->alerts
            )
        );

        // Cosntruct left buttons template
        $navigation_buttons_left = new Template('templates/navigation_buttons.php',
            array(
                'active'        => $active,
                'buttons'       => $buttons_left,
            )
        );

        // Construct right buttons template
        $navigation_buttons_right = new Template('templates/navigation_buttons.php',
            array(
                'active'        => $active,
                'buttons'       => $buttons_right,
            )
        );

        // Construct navigation template
        $navigation = new Template('templates/navigation.php',
            array(
                'active'                    => $active,
                'navigation_buttons_left'   => $navigation_buttons_left,
                'navigation_buttons_right'  => $navigation_buttons_right,
            )
        );

        // Cosntruct page template
        $page = new Template('templates/page.php',
            array(
                'title'         => 'FeedReader :: '.ucfirst($page_name).' :: '.ucfirst($view_name),
                'navigation'    => $navigation,
                'alerts'        => $alerts,
                'content'       => $content,
            )
        );

        // Output rendered page
        echo $page->Render();
    }    
}
