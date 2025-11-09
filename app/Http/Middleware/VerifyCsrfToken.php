<?php

namespace App\Http\Middleware;

class VerifyCsrfToken
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    const EXCEPT = [
        'add-source',
        'test/*',
        '/adminhtml/design/home-setup',
    ];
}
