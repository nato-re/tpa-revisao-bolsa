<?php

namespace App\Http\Controllers;

use App\Models\Ativo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AtivoController extends Controller
{
    public function index()
    {
        $ativos = Ativo::with(['vendedor'])->get();
        return view('ativos.index', compact('ativos'));
    }

    public function create()
    {
        return view('ativos.create');
    }

    public function store(Request $request)
    {
        $ativo_data = $request->validate([
            'ticker' => ['required', 'unique:ativos,ticker', 'regex:/^[A-Z]{4}\d{1,2}$/m'],
            'empresa'=> ['required', 'max:255'],
            'setor'=> ['required', 'in:Bancos,Energia,Mineração,Petróleo,Varejo'],
            'preco'=> ['required', 'numeric', 'min:0.01'],
            'estoque'=> ['required', 'numeric', 'min:0'],
        ]);
        $vendedor_id = Auth::id(); ///Auth::user()->id
        $ativo_data['vendedor_id'] = $vendedor_id;
        $ativo = Ativo::create($ativo_data);

        return redirect()->route('ativos.index');
    }
}
