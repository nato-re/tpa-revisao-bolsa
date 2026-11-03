<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AtivoController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        return view('ativos.create');
    }

    public function store(Request $request)
    {
        //
    }
}
