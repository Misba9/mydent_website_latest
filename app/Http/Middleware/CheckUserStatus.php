<?php

namespace App\Http\Middleware;

use Closure;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CheckUserStatus
 */
class CheckUserStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Block before the controller runs so disabled/unverified users
        // cannot cause side effects on the request.
        if (Auth::check()) {
            if (getSettingValue('email_verified') && ! getLogInUser()->email_verified_at) {
                Auth::logout();
                Flash::error('Please verify your email.');

                return redirect()->to('login');
            }

            if (! getLogInUser()->status) {
                Auth::logout();
                Flash::error('Your Account is currently disabled, please contact to administrator.');

                return redirect()->to('login');
            }
        }

        return $next($request);
    }
}
