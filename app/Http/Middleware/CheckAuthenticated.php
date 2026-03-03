<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->get('isLogin') || !session()->get('user')) {
            session()->flash('error', 'Silakan login terlebih dahulu.');
            return redirect()->to('/login');
        }
        return $next($request);
    }
}
