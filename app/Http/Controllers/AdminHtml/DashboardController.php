<?php

namespace App\Http\Controllers\AdminHtml;

use App\Api\Data\AdminUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class DashboardController extends Controller
{
    const ADMIN_USER = 'adminUser';

    protected $request;
    protected $adminUser;

    function __construct(
        Request $request,
        AdminUser $adminUser
    ) {
        $this->request = $request;
        $this->adminUser = $adminUser;
    }

    /**
     *
     */
    public function home(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view('adminhtml.home');
    }

    /**
     *
     */
    public function login(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view('adminhtml.pages.sign-in');
    }

    public function signUp(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view('adminhtml.pages.sign-up');
    }

    public function register()
    {
        $registerSuceess = false;
        if ($registerSuceess) {
            // return redirect()->route('dashboard')->with('message', 'login success!');
            return $this->loginPost();
        }
        return redirect()->back()->with('message', 'register adminUser  error, please check email and password again!');
    }

    public function logout(): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        Session::remove(self::ADMIN_USER);
        Session::save();
        return redirect()->to('/adminhtml/login')->with('message', 'logout success. Goodbye!...');
    }

    public function loginPost(): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        Session::push(self::ADMIN_USER, $this->adminUser->setData([
            'name' => 'tha nan',
            "email" => $this->request->get('email')
        ]));
        Session::save();
        return redirect()->intended("adminhtml")->with('message', 'login success!');
    }
}
