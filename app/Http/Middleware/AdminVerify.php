<?php

namespace App\Http\Middleware;

use App\Http\Controllers\AdminHtml\DashboardController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd(Session::get(DashboardController::ADMIN_USER);
        if (!$request->session()->get(DashboardController::ADMIN_USER)) {
            // abort(404);
            /**
             * ->setIntendedUrl($request->getPathInfo()) : gasn url hien taji cho  IntendedUrl cua redirect
             * ->to('adminhtml/login'): gasn url se chuyen huowng qua
             * ->with('message', 'please login before redirect dashboard!'): gan flash session cho dia chi ke tiep.
             */
            return redirect()->setIntendedUrl($request->getPathInfo())->to('adminhtml/login')->with('message', 'please login before redirect!');
        }
        return $next($request);
    }
}
