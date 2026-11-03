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
        
        $login_data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if(Auth::attempt($login_data)){
            $request->session()->regenerate();
            return redirect()->route('ativos.index');
        } 
        return back();
        
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        return redirect()->route('ativos.index');
    }
}
