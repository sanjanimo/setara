<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route($this->redirectRouteName());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            if (! Auth::user()->is_active) {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun kamu tidak aktif. Hubungi admin jika ini kesalahan.',
                ]);
            }

            $request->session()->regenerate();

            $user = Auth::user();

            ActivityLog::record(
                'auth.login',
                "{$user->name} masuk ke sistem.",
                $user,
                $user->id
            );

            return redirect()->intended(route($this->redirectRouteName()));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Email atau password salah.',
            ]);
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route($this->redirectRouteName());
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role' => [
                'required',
                Rule::in([
                    User::ROLE_DONATUR,
                    User::ROLE_RELAWAN,
                    User::ROLE_PANTI,
                ]),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'organization_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'organization_name' => $validated['organization_name'] ?? null,
            'is_active' => true,
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        ActivityLog::record(
            'auth.register',
            "{$user->name} mendaftar sebagai {$user->role}.",
            $user,
            $user->id
        );

        return redirect()->route($this->redirectRouteName());
    }

    public function logout(Request $request)
    {
        ActivityLog::record(
            'auth.logout',
            Auth::user()->name . ' keluar dari sistem.',
            Auth::user(),
            Auth::id()
        );
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    protected function redirectRouteName(): string
    {
        return match (Auth::user()->role) {
            User::ROLE_ADMIN => 'admin.dashboard',
            User::ROLE_PANTI => 'panti.dashboard',
            User::ROLE_RELAWAN => 'relawan.dashboard',
            User::ROLE_DONATUR => 'donatur.dashboard',
            default => 'home',
        };
    }
}
