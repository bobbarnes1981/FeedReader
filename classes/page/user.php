<?php

namespace Page;

// Page user
class User extends \Page
{
    // Required user roles
    protected $secure =array('view' => 'admin');

    // View view
    public function View_View($request)
    {
        $user = \Auth::LoggedIn();

        // Parameters
        $page = (isset($request->get['page'])?$request->get['page']:1);
        
        // Pagination
        $per_page = 20;
        $users_count = \Database\User::Count();
        $users = \Database\User::Get($page, $per_page);
        $max = (int)($users_count / $per_page);
        if ($max < ($users_count / $per_page))
        {
            $max = $max + 1;
        }
        $pagination = new \Template('templates/pagination.php',
            array(
                'url' => '/user/view?&page=',
                'page' => $page,
                'max' => $max,
            )
        );

        return array('users' => $users, 'pagination' => $pagination, 'page' => $page);
    }
}
