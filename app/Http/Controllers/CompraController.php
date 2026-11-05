<?php

namespace App\Http\Controllers;

use App\Models\Ativo;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompraController extends Controller
{
    public function index()
    {
        $vendas = Auth::user()->vendas()->with('ativos')->get();
        //$vendas = Venda::where('cliente_id', Auth::id())->with('ativos')->get();
        return view('compras.index', compact('vendas'));
    }

    public function create()
    {
        $ativos = Ativo::orderBy('ticker')->get();

        return view('compras.create', compact('ativos'));
    }

    public function store(Request $request)
    {
        $itens = $request->input('itens', []);
        $itens_filtrados = collect($itens)->filter(function ($quantidade){
            return $quantidade > 0;
        });
        // Transaction
        $venda = Venda::create(['cliente_id' => Auth::id()]);
        
        $ativos = Ativo::findMany($itens_filtrados->keys());

        foreach($ativos as $ativo){
            $venda->ativos()->attach($ativo->id, [
                'preco' => $ativo->preco,
                'quantidade' => $itens_filtrados[$ativo->id]
            ]);
        }
        return redirect()->route('compras.index');
    }
}
