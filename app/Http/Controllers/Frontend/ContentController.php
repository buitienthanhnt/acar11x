<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentController extends Controller
{
    /**
     *
     */
    public function listStatus(Request $request): \Inertia\Response {
        return Inertia::render('List', $request->all());
    }
}
