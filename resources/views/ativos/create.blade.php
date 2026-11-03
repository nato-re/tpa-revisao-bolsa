@extends('layouts.app')

@section('title', 'Cadastrar Ação — Home Broker TPA')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm p-4">
            <h3 class="fw-bold mb-3">➕ Cadastrar Ação</h3>
            <form method="POST" action="{{ route('ativos.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="ticker" class="form-label text-secondary">Ticker</label>
                    <input type="text" name="ticker" id="ticker" value="{{ old('ticker') }}" class="form-control @error('ticker') is-invalid @enderror">
                    @error('ticker') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="empresa" class="form-label text-secondary">Empresa</label>
                    <input type="text" name="empresa" id="empresa" value="{{ old('empresa') }}" class="form-control @error('empresa') is-invalid @enderror">
                    @error('empresa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="setor" class="form-label text-secondary">Setor</label>
                    <select name="setor" id="setor" class="form-select @error('setor') is-invalid @enderror">
                        <option value="">Selecione…</option>
                        <option value="Bancos" @selected(old('setor') == 'Bancos')>Bancos</option>
                        <option value="Energia" @selected(old('setor') == 'Energia')>Energia</option>
                        <option value="Mineração" @selected(old('setor') == 'Mineração')>Mineração</option>
                        <option value="Petróleo" @selected(old('setor') == 'Petróleo')>Petróleo</option>
                        <option value="Varejo" @selected(old('setor') == 'Varejo')>Varejo</option>
                    </select>
                    @error('setor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="cotacao" class="form-label text-secondary">Cotação (R$)</label>
                    <input type="number" step="0.01" name="cotacao" id="cotacao" value="{{ old('cotacao') }}" class="form-control @error('cotacao') is-invalid @enderror">
                    @error('cotacao') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="estoque" class="form-label text-secondary">Ações ofertadas</label>
                    <input type="number" name="estoque" id="estoque" value="{{ old('estoque') }}" class="form-control @error('estoque') is-invalid @enderror">
                    @error('estoque') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Cadastrar</button>
            </form>
        </div>
    </div>
</div>
@endsection
