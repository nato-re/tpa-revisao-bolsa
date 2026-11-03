@extends('layouts.app')

@section('content')
<div class="card shadow-sm p-5 mb-4">
    <h1 class="fw-bold">Home Broker TPA 📈</h1>
    <p class="lead text-secondary mb-4">
        Corretores colocam lotes de ações à venda e investidores montam a carteira.
    </p>
    <div>
        <a href="{{ route('ativos.index') }}" class="btn btn-primary btn-lg me-2">Ver Ações</a>
        <a href="{{ route('compras.create') }}" class="btn btn-outline-light btn-lg">Comprar ações</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card h-100 p-3">
            <h5 class="fw-bold">🏪 Para corretores</h5>
            <p class="text-secondary mb-0">Entre com sua conta de corretor(a) e coloque novos lotes de ações à venda.</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100 p-3">
            <h5 class="fw-bold">🛒 Para investidores</h5>
            <p class="text-secondary mb-0">Escolha uma ou mais ações, informe as quantidades e envie a ordem de compra.</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100 p-3">
            <h5 class="fw-bold">🧾 Histórico</h5>
            <p class="text-secondary mb-0">Acompanhe em "Minhas ordens" tudo o que você já comprou, com o total investido em cada ordem.</p>
        </div>
    </div>
</div>
@endsection
