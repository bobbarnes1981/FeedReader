<?php

namespace Page;

// Page settings
class Settings extends \Page
{
    // Required user roles
    protected $secure =array('view' => 'user');

    // View view
    public function View_View($request)
    {
        $user = \Auth::LoggedIn();

        $test_a_opt = 'test';
        $test_a_checked = $user->GetSetting('test_a');

        $test_b_opt = array(
            'a'     => 'A',
            'b'     => 'B',
            'c'     => 'C',
            'd'     => 'D',
        );
        $test_b_checked = $user->GetSetting('test_b');

        // If posted
        if ($request->method == 'POST')
        {
            // Get parameters
            $test_a_checked->value = (isset($request->post['test_a'])?$request->post['test_a']:null);
            $test_b_checked->value = (isset($request->post['test_b'])?$request->post['test_b']:null);
            // Save
            $test_a_checked->Save();
            $test_b_checked->Save();
            // Notify user
            $this->alerts['success'][] = 'Settings saved.';
        }
    
        return array('user' => $user, 'test_a_opt' => $test_a_opt, 'test_a_checked' => $test_a_checked, 'test_b_opt' => $test_b_opt, 'test_b_checked' => $test_b_checked);
    }
}
