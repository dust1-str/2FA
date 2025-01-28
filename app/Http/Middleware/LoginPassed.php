<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class LoginPassed
 *
 * This middleware ensures that the user has passed the Login form before accessing OTP form.
 * Also, allows the user to try again if the OTP is incorrect.
 *
 * @package App\Http\Middleware
 */
class LoginPassed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request instance.
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next The next middleware to call.
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the request comes from the 'login' route
        $referer = $request->headers->get('referer');
        
        if ($referer && strpos($referer, route('login')) !== false) {
            return $next($request);
        }

        // Check if the 'passed' session variable is false
        if ($request->session()->get('passed') === false) {
            // Clear the 'passed' session variable
            $request->session()->forget('passed');
            return $next($request);
        }

        // Redirect to the login form if the request does not come from the 'login' route or the 'passed' session variable is not false
        return redirect()->route('login');
    }
}