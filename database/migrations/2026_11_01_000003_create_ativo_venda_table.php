<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela pivô da relação N:M entre vendas e ativos.
     * Guarda a quantidade e o preço do produto no momento da compra.
     */
    public function up(): void
    {
        Schema::create('ativo_venda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venda_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ativo_id')->constrained('ativos');
            $table->integer('quantidade');
            $table->decimal('preco_unitario', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ativo_venda');
    }
};
