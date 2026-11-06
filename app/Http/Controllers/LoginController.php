<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function attempt(Request $request)
    {
        $login_form = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $login_sucesso = Auth::attempt($login_form);
        if($login_sucesso){
            $request->session()->regenerateToken();
            return redirect()->route('home');
        }
        return back()->withErrors('Email ou senha inválidos');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        return redirect()->route('home');
    }
}
