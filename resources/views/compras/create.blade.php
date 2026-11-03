@extends('layouts.app')

@section('title', 'Comprar ações — Home Broker TPA')

@section('content')
<h2 class="fw-bold mb-3">🛒 Nova ordem de compra</h2>
<p class="text-secondary">Informe a quantidade de cada item. Itens com quantidade 0 ficam de fora da compra.</p>

<form method="POST" action="{{ route('compras.store') }}">
    @csrf
    <div class="card shadow-sm mb-3">
        <table class="table table-dark align-middle mb-0">
            <thead>
                <tr>
                    <th>Ação</th>
                    <th>Preço</th>
                    <th>Ações disponíveis</th>
                    <th style="width: 9rem">Quantidade</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ativos ?? [] as $ativo)
                    <tr>
                        <td>{{ $ativo->ticker }}</td>
                        <td>R$ {{ number_format($ativo->preco, 2, ',', '.') }}</td>
                        <td>{{ $ativo->estoque }}</td>
                        <td>
                            <input type="number" min="0" name="itens[{{ $ativo->id }}]"
                                   value="{{ old('itens.' . $ativo->id, 0) }}" class="form-control">
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-4">Nenhuma ação para mostrar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <button type="submit" class="btn btn-primary fw-bold">Enviar ordem</button>
</form>
@endsection
