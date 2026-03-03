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
            if ($this->isAjax($request)) {
                return response()->json(['success' => false, 'message' => 'Sesi habis. Silakan login kembali.'], 401);
            }
            session()->flash('error', 'Silakan login terlebih dahulu.');
            return redirect()->to('/login');
        }
        return $next($request);
    }

    private function isAjax(Request $request): bool
    {
        return $request->wantsJson()
            || $request->ajax()
            || str_contains($request->header('Content-Type', ''), 'application/json')
            || str_contains($request->header('Accept', ''), 'application/json');
    }
}
