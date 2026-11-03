<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAtivoRequest;
use App\Models\Ativo;
use Illuminate\Support\Facades\Auth;

class AtivoController extends Controller
{
    public function index()
    {
        $ativos = Ativo::with('vendedor')->orderBy('ticker')->get();

        return view('ativos.index', compact('ativos'));
    }

    public function create()
    {
        return view('ativos.create');
    }

    public function store(StoreAtivoRequest $request)
    {
        $dados = $request->validated();
        $dados['vendedor_id'] = Auth::id();

        Ativo::create($dados);

        return redirect()->route('ativos.index')->with('sucesso', 'Ação cadastrada!');
    }
}
