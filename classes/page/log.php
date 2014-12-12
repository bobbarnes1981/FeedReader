<?php

namespace Page;

// Page log
class Log extends \Page
{
    // Required user roles
    protected $secure =array('view' => 'admin');

    // View view
    public function View_View($request)
    {
        $user = \Auth::LoggedIn();

        // Parameters
        $page = (isset($request->get['page'])?$request->get['page']:1);
        $filter = (isset($request->get['filter'])?$request->get['filter']:null);
        
        // Pagination
        $per_page = 20;
        $logs_count = \Database\Log::Count($filter);
        $logs = \Database\Log::Get($filter, $page, $per_page);
        $max = (int)($logs_count / $per_page);
        if ($max < ($logs_count / $per_page))
        {
            $max = $max + 1;
        }
        $pagination = new \Template('templates/pagination.php',
            array(
                'url' => '/log/view?filter='.$filter.'&page=',
                'page' => $page,
                'max' => $max,
            )
        );

        return array('logs' => $logs, 'pagination' => $pagination, 'page' => $page, 'filter' => $filter);
    }
}
