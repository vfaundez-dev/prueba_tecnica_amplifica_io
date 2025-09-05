<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    
    public function showLoginForm() {
        return view('auth.login');
    }

    public function showRegisterForm() {
        return view('auth.register');
    }

    public function login(Request $request) {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);
        
        $remember = $request->has('remember');

        if ( Auth::attempt($credentials, $remember) ) {
            $request->session()->regenerate();
            return redirect()->route('dashboard.index');
        }

        return back()->withErrors([
            'email' => 'Correo o contraseña incorrectos.',
        ])->withInput($request->except('password'));
    }

    public function register(Request $request) {

        $registerData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|same:password'
        ]);

        if ( User::where('email', $registerData['email'])->exists() ) {
            return back()->withErrors(['email' => 'El correo electrónico ya está registrado.'])
                    ->withInput( $request->only('email', 'name') );
        }

        $user = User::create([
            'name' => $registerData['name'],
            'email' => $registerData['email'],
            'password' => bcrypt($registerData['password'])
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('dashboard.index');

    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
