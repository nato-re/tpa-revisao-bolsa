<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\Ativo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index()
    {
        $vendas = Venda::where('cliente_id', Auth::id())
            ->with('ativos')
            ->latest()
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
        $itens = collect($request->input('itens', []))->filter(fn ($quantidade) => (int) $quantidade > 0);

        if ($itens->isEmpty()) {
            return back()->withErrors(['itens' => 'Escolha pelo menos um produto.']);
        }

        $produtos = Ativo::findMany($itens->keys());

        // Bônus: não vender acima do estoque.
        foreach ($produtos as $produto) {
            if ($itens[$produto->id] > $produto->estoque) {
                return back()->withInput()->withErrors([
                    'itens' => "Estoque insuficiente para {$produto->ticker}.",
                ]);
            }
        }

        DB::transaction(function () use ($itens, $produtos) {
            $venda = Venda::create(['cliente_id' => Auth::id()]);

            foreach ($produtos as $produto) {
                $venda->ativos()->attach($produto->id, [
                    'quantidade' => $itens[$produto->id],
                    'preco_unitario' => $produto->preco,
                ]);

                // Bônus: baixa no estoque.
                $produto->decrement('estoque', $itens[$produto->id]);
            }
        });

        return redirect()->route('compras.index')->with('sucesso', 'Ordem executada!');
    }
}
