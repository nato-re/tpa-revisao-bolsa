@extends('layouts.app')

@section('title', 'Minhas ordens — Home Broker TPA')

@section('content')
<h2 class="fw-bold mb-3">🧾 Minhas ordens</h2>

@forelse ($vendas ?? [] as $venda)
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between">
            <strong>Ordem #{{ $venda->id }}</strong>
            <span class="text-secondary">{{ $venda->created_at?->format('d/m/Y H:i') }}</span>
        </div>
        <table class="table table-dark mb-0">
            <thead>
                <tr>
                    <th>Ação</th>
                    <th>Quantidade</th>
                    <th>Preço unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($venda->ativos as $ativo)
                    <tr>
                        <td>{{ $ativo->ticker }}</td>
                        <td>{{ $ativo->pivot->quantidade }}</td>
                        <td>R$ {{ number_format($ativo->pivot->preco, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($ativo->pivot->quantidade * $ativo->pivot->preco, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="card-footer text-end fw-bold">
            Total: R$ {{ number_format($venda->ativos->sum(fn ($item) => $item->pivot->quantidade * $item->pivot->preco), 2, ',', '.') }}
        </div>
    </div>
@empty
    <p class="text-secondary">Você ainda não enviou nenhuma ordem.</p>
@endforelse
@endsection
