<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }


    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        if ($request->loggedInViaContactGuard()) {
            return redirect()->intended('/customer/dashboard');
        }

        $user = Auth::guard('web')->user();
        $sync = resolve(\App\Services\Core\Auth\UserRoleSyncService::class);

        if ($user && ($user->isAppAdmin() || $sync->hasPortalAccess($user))) {
            return redirect()->intended('/admin');
        }

        if ($user && $sync->hasHrmAccess($user)) {
            return redirect()->intended('/dashboard');
        }

        return redirect()->intended(\Session::get(RouteServiceProvider::HOME.'redirect_url', RouteServiceProvider::HOME));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        Auth::logout();
        Session::flush();
        return redirect('/');
    }
}
