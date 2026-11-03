<?php

namespace App\Http\Controllers;

use App\Models\Ativo;
use App\Models\Venda;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompraController extends Controller
{
    public function index()
    {
        $vendas = Venda::where('cliente_id', Auth::id())->with('ativos')->get();
        return view('compras.index', compact('vendas'));

    }

    public function create()
    {
        $ativos = Ativo::orderBy('ticker')->get();

        return view('compras.create', compact('ativos'));
    }

    public function store(Request $request)
    {
        $venda = Venda::create(['cliente_id' => Auth::id()]);

        $itens = collect($request->input('itens' , []))->filter(fn ($quantidade) => $quantidade > 0);
        $ativos = Ativo::findMany($itens->keys());

        foreach ($ativos as $ativo) {
            $ativo_input = $itens[$ativo->id];
            $venda->ativos()->attach($ativo->id, [
                'quantidade' => $ativo_input, 
                'preco_unitario' => $ativo->cotacao]
            );
        }
        return redirect()->route('compras.index');
    }
}
