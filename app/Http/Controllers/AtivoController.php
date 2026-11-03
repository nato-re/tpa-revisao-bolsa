<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAtivoRequest;
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

    public function store(StoreAtivoRequest $request)
    {
        $ativo_data = $request->validated();

        Ativo::create([
            'ticker' => $ativo_data['ticker'],
            'empresa' => $ativo_data['empresa'],
            'setor' => $ativo_data['setor'],
            'preco' => $ativo_data['preco'],
            'estoque' => $ativo_data['estoque'],
            'vendedor_id' => Auth::id()
        ]);

        return redirect()->route('ativos.index');
    }
}
