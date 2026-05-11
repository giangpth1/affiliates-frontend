<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected ApiService $api)
    {
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->api->login(
            $request->input('email'),
            $request->input('password')
        );

        if ($result['success']) {
            // Nếu user chọn "Ghi nhớ đăng nhập", set session lifetime = 30 ngày
            if ($request->has('remember') && $request->boolean('remember')) {
                config(['session.lifetime' => 43200]); // 30 days in minutes
                session()->put('remember_login', true);
            }
            
            return redirect()->route('products.index');
        }

        return back()->withErrors(['email' => $result['error']])->withInput();
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'display_name' => 'required|min:2|max:50',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $result = $this->api->register([
            'display_name' => $request->input('display_name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        if ($result['success']) {
            return redirect()->route('products.index');
        }

        return back()->withErrors(['email' => $result['error']])->withInput();
    }

    public function logout()
    {
        $this->api->logout();
        return redirect()->route('login');
    }
}
