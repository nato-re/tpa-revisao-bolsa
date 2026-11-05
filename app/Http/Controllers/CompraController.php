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
        $itens = $request->input('itens', []);
        $itens_com_quantidade = collect($itens)->filter(fn ($quantidade) => $quantidade > 0);
        $ativos = Ativo::findMany($itens_com_quantidade->keys());

        if($ativos->isEmpty()){
            back();
        }
        $venda = Venda::create(['cliente_id' => Auth::id()]);

        foreach ($ativos as $ativo) {
            $venda->ativos()->attach($ativo->id, [
                'quantidade' => $itens_com_quantidade[$ativo->id],
                'preco_unitario' => $ativo->preco,
            ]);
        }
        return redirect()->route('compras.index');
    }
}
