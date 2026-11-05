@extends('layouts.app')

@section('title', 'Ações — Home Broker TPA')

@section('content')
<h2 class="fw-bold mb-3">📦 Ações à venda</h2>

<div class="card shadow-sm">
    <table class="table table-dark table-hover mb-0">
        <thead>
            <tr>
                <th>Ticker</th>
                <th>Empresa</th>
                <th>Setor</th>
                <th>Cotação (R$)</th>
                <th>Ações ofertadas</th>
                <th>Corretor(a)</th>
            </tr>
            {{ $var2 }}
        </thead>
        <tbody>
            @forelse ($ativos ?? [] as $ativo)
                <tr>
                    <td>{{ $ativo->ticker }}</td>
                    <td>{{ $ativo->empresa }}</td>
                    <td>{{ $ativo->setor }}</td>
                    <td>R$ {{ number_format($ativo->preco, 2, ',', '.') }}</td>
                    <td>{{ $ativo->estoque }}</td>
                    <td>{{ $ativo->vendedor->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="99" class="text-center text-secondary py-4">Nenhuma ação para mostrar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
