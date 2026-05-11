<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ApiService;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    public function __construct(protected ApiService $api)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (!session('access_token')) {
            return redirect()->route('login');
        }

        $user = $this->api->getCurrentUser();
        if (!$user) {
            session()->forget(['access_token', 'refresh_token', 'user']);
            return redirect()->route('login')->with('error', 'Phiên đăng nhập hết hạn');
        }

        view()->share('currentUser', session('user'));

        return $next($request);
    }
}
