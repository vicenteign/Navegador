<?php

namespace App\Http\Middleware;

use Closure;

class IsProfesor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user() == null)
            return redirect('/');
        if(auth()->user()->isProfesor())
            return $next($request);
        return redirect('home');
    }
}
