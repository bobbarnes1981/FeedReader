<?php

// Auth
class Auth
{
    // Login using username and password
    public static function Login($username, $password)
    {
		$source = $_SERVER['REMOTE_ADDR'];

		// Check if black listed
		if (Database\Blacklist::Blacklisted($source, $username))
        {
            // Append a blacklist entry
            Database\Blacklist::Add($source, $username, $password);
            // Fail
            return false;
        }

        // Check password
        $user_id = Auth::PasswordCheck($username, $password);

        // If password is correct
        if ($user_id)
        {
            // Set the session variable
            Session::Set('user_id', $user_id);
            // Success
            return true;
        }
        else
        {
            // Append a blacklist entry
            Database\Blacklist::Add($source, $username, $password);
            // Fail
            return false;
        }
    }

    // Check the username and password are correct
    public static function PasswordCheck($username, $password)
    {
        // Build query
        $query = 'SELECT * FROM users WHERE username = "'.Db::Instance()->Escape($username).'" AND password = "'.Db::Instance()->Escape(Auth::HashPassword($password)).'";';
        // Execute query
        $res = Db::Instance()->Query($query);
        // If results
        if ($res && $res->num_rows)
        {
            // Return user id
            $row = $res->fetch_assoc();
            return $row['id'];
        }
        else
        {
            // Return null
            return null;
        }
    }

    // Hash the password
    public static function HashPassword($password)
    {
        // Return hashed password
        return crypt($password, Config::Get('auth', 'salt'));
    }

    // Logout the current user
    public static function Logout()
    {
        // Remove user cache
        Auth::$user = null;

        // Unset session variable
        Session::Del('user_id');
    }

    // Cached user
    private static $user = null;

    // Get the logged in user
    public static function LoggedIn()
    {
        $session_id = Session::Get('user_id');

        // If session variable set
        if ($session_id != null)
        {
            // User cached
            if (Auth::$user != null && Auth::$user->id == $session_id)
            {
                return Auth::$user;
            }
            // Build user object from session variable
            $user = Database\User::Factory($session_id);
            // If user loaded
            if ($user->Loaded())
            {
                // Cache user
                Auth::$user = $user;
                // Return user
                return Auth::$user;
            }
        }
        // Return null
        return null;
    }
}
