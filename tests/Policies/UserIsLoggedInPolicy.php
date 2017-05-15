<?php

require_once __DIR__.'/../../src/Access/Policy.php';

use Components\Access\Access\Policy as Policy;

class UserIsLoggedInPolicy extends Policy
{
    protected $name = 'user is logged in';

    protected function policy($cookies)
    {
        return isset($cookies['user_id']);
    }
}
