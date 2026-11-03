<?php

namespace App\Http\Controllers;

use App\Models\Ativo;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $ativos = Ativo::orderBy('ticker')->get();

        return view('compras.create', compact('ativos'));
    }

    public function store(Request $request)
    {
        //
    }
}
