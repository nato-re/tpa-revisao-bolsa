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
        // dd($request);
        $ativo_payload = $request->validated();
        $ativo = Ativo::create([
            'ticker' => $ativo_payload['ticker'],
            'empresa' => $ativo_payload['empresa'],
            'setor' => $ativo_payload['setor'],
            'preco' => $ativo_payload['preco'],
            'estoque' => $ativo_payload['estoque'],
            'vendedor_id' => Auth::id(),
        ]);

        return redirect()->route('ativos.index');
    }
}
