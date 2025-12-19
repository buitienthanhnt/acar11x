<?php

namespace Thanhnt\Ahomeglobal\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Validation\Rules;

final class AuthController extends Controller
{
	public function __construct()
	{
		// throw new \Exception('Not implemented');
	}

	/**
	 * @return \Inertia\Response
	 */
	public function loginPage()
	{
		if (Auth::user()) {
			return redirect('/');
		}
		return Inertia::render('Ahomeglobal/Screens/LogIn', []);
	}

	public function loginAction(LoginRequest $request): RedirectResponse
	{
		$request->authenticate();

		$request->session()->regenerate();

		return redirect()->intended();
	}

	public function createAccount()
	{
		return Inertia::render('Ahomeglobal/Screens/CreateAccount', []);
	}

	public function registerAccount(Request $request): RedirectResponse
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
			'password' => ['required', 'confirmed', Rules\Password::defaults()],
		]);

		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
		]);

		event(new Registered($user));

		Auth::login($user);

		return redirect('/');
	}
}
