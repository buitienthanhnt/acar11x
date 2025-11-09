<?php

namespace Database\Configs;

final class AdminPermission
{

    const ACTION_LIST = 0;
    const ACTION_CREATE = self::class.'_create';
    const ACTION_UPDATE = 2;
    const ACTION_DELETE = 3;

    // admin user table: | id | isRoot | active | email | password | =>
    // admin user permission: | admin_user_id | key(status) | value(0,2,3) |

    const STATUS = [
        'key' => 'status', // value of key must be same as name of const
        'values' => [
            'list' => self::ACTION_LIST,
            'create' => self::ACTION_CREATE,
            'update' => self::ACTION_UPDATE,
            'delete' => self::ACTION_DELETE
        ]
    ];
}
