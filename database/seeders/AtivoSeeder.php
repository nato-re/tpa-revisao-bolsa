<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ativo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

/**
 * CONTRATO DO R1: este seeder define o nome da model (Ativo), da tabela
 * (ativos) e das colunas. Sua migration e sua model precisam ser
 * compatíveis com ele. Não altere este arquivo.
 */
class AtivoSeeder extends Seeder
{
    public function run(): void
    {
        $ativos = [
            ['ticker' => 'PETR4', 'empresa' => 'Petrobras', 'setor' => 'Petróleo', 'cotacao' => 38.2, 'estoque' => 500],
            ['ticker' => 'VALE3', 'empresa' => 'Vale', 'setor' => 'Mineração', 'cotacao' => 61.75, 'estoque' => 400],
            ['ticker' => 'ITUB4', 'empresa' => 'Itaú Unibanco', 'setor' => 'Bancos', 'cotacao' => 34.1, 'estoque' => 600],
            ['ticker' => 'MGLU3', 'empresa' => 'Magazine Luiza', 'setor' => 'Varejo', 'cotacao' => 9.85, 'estoque' => 1000],
            ['ticker' => 'TAEE11', 'empresa' => 'Taesa', 'setor' => 'Energia', 'cotacao' => 35.4, 'estoque' => 250],
        ];

        $colunas = [...array_keys($ativos[0]), 'vendedor_id'];

        if (! class_exists(Ativo::class) || ! Schema::hasColumns('ativos', $colunas)) {
            $this->command->warn('Ações não semeados: faça o R1 (model Ativo + migration com as colunas '
                . implode(', ', $colunas) . ') e rode "php artisan migrate:fresh --seed".');

            return;
        }

        $vendedores = User::where('tipo', 'vendedor')->orderBy('id')->pluck('id');

        foreach ($ativos as $i => $dados) {
            Ativo::forceCreate($dados + ['vendedor_id' => $vendedores[$i % $vendedores->count()]]);
        }
    }
}
