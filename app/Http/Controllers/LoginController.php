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
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if(Auth::attempt($validated)){
            $request->session()->regenerate();
            return redirect()->route('ativos.index');
        }

        return back()->withErrors(['loginError' => 'Nome ou senha estão errados'])->withInput(['name']);
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        return redirect()->route('login',);
    }
}
