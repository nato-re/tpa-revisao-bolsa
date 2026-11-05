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
        $corpo_validado = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $login_deu_certo = Auth::attempt($corpo_validado);
        if($login_deu_certo){
            $request->session()->regenerate();
            return redirect()->route('home');
        } else {
            return redirect()->route('login');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        return redirect()->route('login');
    }
}
