<?php

namespace App\Http\Controllers;

use App\Models\Ativo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AtivoController extends Controller
{
    public function index()
    {
        $ativos = Ativo::with('vendedor')->get();
        return view('ativos.index', compact('ativos'));
    }

    public function create()
    {
        return view('ativos.create');
    }

    public function store(Request $request)
    {
        $validated_form = $request->validate([
            'ticker' => ['required', 'unique:ativos,ticker', 'regex:/[A-Z]{4}\d{1,2}$/m'],
            'empresa'=> ['required', 'max:255'],
            'setor'=> ['required', 'in:Bancos,Energia,Mineração,Petróleo,Varejo'],
            'preco'=> ['required', 'numeric', 'min:0.01'],
            'estoque'=> ['required', 'numeric', 'min:0'],
        ]);
        $validated_form['vendedor_id'] = Auth::id();
        Ativo::create($validated_form);

        return redirect()->route('ativos.index');
    }
}
