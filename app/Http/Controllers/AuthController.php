<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'employee') {
                return redirect()->route('employee-dashboard');
            }
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,employee',
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            if ($user->role !== $credentials['role']) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withErrors(['email' => 'Invalid role for this login page'])
                    ->withInput($request->only('email'));
            }
            $redirectRoute = 'dashboard';
            
            if ($user->role === 'employee') {
                $redirectRoute = 'employee-dashboard';
            }
            
            return redirect()->route($redirectRoute)
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()
            ->withErrors(['email' => 'Invalid email or password'])
            ->withInput($request->only('email'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'Logged out successfully');
    }
}