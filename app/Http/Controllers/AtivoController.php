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
        $var2 = "";
        return view('ativos.index', compact('ativos', 'var2'));
    }

    public function create()
    {
        return view('ativos.create');
    }

    public function store(StoreAtivoRequest $request)
    {
        $ativo_form_validado = $request->validated();
        $ativo_form_validado['vendedor_id'] = Auth::id();
        Ativo::create($ativo_form_validado);
        // { vendedor_id: Auth::id(), ...ativo_form_validado }
        return redirect()->route('ativos.index');
    }
}
