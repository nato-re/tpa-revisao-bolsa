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
        $ativo_data['vendedor_id'] = Auth::id();
        Ativo::create($ativo_data);

        return redirect()->route('ativos.index');
    }
}
