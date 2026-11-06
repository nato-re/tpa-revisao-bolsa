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
        $vendas = Venda::where('cliente_id', Auth::id())
            ->with('ativos')
            ->get();
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
        $itens_filtrados = collect($itens)->filter(fn($quantidade) => $quantidade > 0);
        $ativos = Ativo::findMany($itens_filtrados->keys());

        if($ativos->isEmpty()){
            return back()->withErrors('Não foi possível concluir a requisição');
        }

        $venda = Venda::create(['cliente_id' => Auth::id()]);

        foreach ($ativos as $ativo) {
            $venda->ativos()->attach($ativo->id, [
                'quantidade' => $itens_filtrados[$ativo->id],
                'preco' => $ativo->preco
            ]);
        }
        return redirect()->route('compras.index');
    }
}
