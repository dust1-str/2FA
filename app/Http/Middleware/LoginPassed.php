<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LoginPassed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si la solicitud proviene de la ruta 'login'
        $referer = $request->headers->get('referer');
        
        if ($referer && strpos($referer, route('login')) !== false) {
            return $next($request);
        }

        if ($request->session()->get('otp_passed') === false) {
            $request->session()->forget('otp_passed');
            return $next($request);
        }

        // Redirigir si no proviene de la ruta 'login'
        return redirect()->route('login');
    }
}