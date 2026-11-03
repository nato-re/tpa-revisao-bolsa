@extends('layouts.app')

@section('title', 'Sobre — Home Broker TPA')

@section('content')
<div class="card shadow-sm p-4">
    <h2 class="fw-bold">Sobre o home broker</h2>
    <p class="text-secondary">
        A Home Broker TPA é um projeto didático da disciplina de TPA (COTEMIG).
        O site já está pronto: falta implementar as features novas descritas no enunciado da prova.
    </p>
    <h5 class="fw-bold mt-3">Quem é quem</h5>
    <ul class="text-secondary">
        <li><strong>Corretor(a):</strong> cadastra Ações no catálogo.</li>
        <li><strong>Investidor(a):</strong> compra Ações e consulta as próprias compras.</li>
        <li><strong>Visitante:</strong> só navega pelo catálogo.</li>
    </ul>
</div>
@endsection
