<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAtivoRequest;
use App\Models\Ativo;
use Auth;
use Illuminate\Http\Request;

class AtivoController extends Controller
{
    public function index()
    {
        $ativos = Ativo::paginate(50);
        return view('ativos.index', compact('ativos'));
    }

    public function create()
    {
        return view('ativos.create');
    }

    public function store(StoreAtivoRequest $request)
    {
        $dados_ativo_validado = $request->validated();
        Ativo::create([
            'ticker' => $dados_ativo_validado['ticker'],
            'empresa' => $dados_ativo_validado['empresa'],
            'setor' => $dados_ativo_validado['setor'],
            'cotacao' => $dados_ativo_validado['cotacao'],
            'estoque' => $dados_ativo_validado['estoque'],
            'vendedor_id' => Auth::id(),
        ]);
        return redirect()->route('ativos.index');   
    }
}
