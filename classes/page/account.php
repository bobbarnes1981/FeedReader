<?php

namespace Page;

// Page account
class Account extends \Page
{
    // Required user roles
    protected $secure =array('logout' => 'user', 'view' => 'user');

    // View login
    public function View_Login($request)
    {
        $username = null;
        $password = null;

        // If posted
        if ($request->method == 'POST')
        {
            // Get parameters
            $username = $request->post['username'];
            $password = $request->post['password'];

            // Attempt login
            if (\Auth::Login($username, $password))
            {
		// Get target URI
                $target = \Session::Get('target');
                if ($target == null)
                {
                    $target = \Url::get('home', 'view');
                }
                // Success
                header('Location: '.$target);
            }
        }

        return array('username' => $username, 'password' => $password);
    }

    // View logout
    public function View_Logout()
    {
        // Logout current user
        \Auth::Logout();

        // Redirect to account login
        header('Location: /account/login');
    }

    // View view
    public function View_View($request)
    {
        $user = \Auth::LoggedIn();

        // Get email
        $email = $user->email;
        // Set new password variable
        $new_password = '';

        // If posted
        if ($request->method == 'POST')
        {
            // Get username
            $username = $request->post['username'];
            // Get password
            $password = $request->post['password'];

            // Get new email
            $email = $request->post['email'];
            // Get new password
            $new_password = $request->post['new_password'];

            // Check credentials
            if (\Auth::PasswordCheck($username, $password))
            {
                // If changing password
                if ($new_password)
                {
                    // Set password
                    $user->password = $new_password;
                    // Save
                    if ($user->Save())
                    {
                        // Success
                        $this->alerts['success'][] = 'Password changed';
                    }
                    else
                    {
                        // Failed
                        $this->alerts['danger'][] = 'Failed to change password';
                    }
                }
                // If changing email
                if ($email && $email != $user->email)
                {
                    // Set email
                    $user->email = $email;
                    // Save
                    if ($user->Save())
                    {
                        // Success
                        $this->alerts['success'][] = 'Email changed';
                    }
                    else
                    {
                        // Failed
                        $this->alerts['danger'][] = 'Failed to change email';
                    }
                }
            }
            else
            {
                // Password incorrect
                $this->alerts['danger'][] = 'Incorrect password';
            }
        }

        return array('user' => $user, 'email' => $email, 'new_password' => $new_password);
    }
}
