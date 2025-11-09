<?php

namespace App\Api;

use App\Api\Data\AdminUser;
use Illuminate\Support\Facades\Session;

class AdminUserApi extends BaseApi
{
    protected $adminUser;

    function __construct(
        AdminUser $adminUser
    )
    {
        $this->adminUser = $adminUser;
    }

    function getAdminUser()
    {
        return Session::get(self::ADMIN_USER);
    }
}
