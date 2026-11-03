<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ativo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Correção automática da prova (REV-BOLSA — Ações): um teste por requisito.
 *
 * Para corrigir um projeto entregue, copie este arquivo para tests/Feature/
 * e rode: php artisan test --filter=ProvaTest
 *
 * Os testes só usam os contratos do enunciado (URLs, campos, seeder, tabelas
 * e a model Ativo), então aceitam implementações diferentes da do gabarito.
 */
class ProvaTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function vendedor(): User
    {
        return User::where('email', 'ana@broker.test')->firstOrFail();
    }

    private function outroVendedor(): User
    {
        return User::where('email', 'bruno@broker.test')->firstOrFail();
    }

    private function cliente(): User
    {
        return User::where('email', 'carla@broker.test')->firstOrFail();
    }

    private function outroCliente(): User
    {
        return User::where('email', 'diego@broker.test')->firstOrFail();
    }

    private function dadosValidos(): array
    {
        return [
            'ticker' => 'BBAS3',
            'empresa' => 'Banco do Brasil',
            'setor' => 'Bancos',
            'preco' => 27.5,
            'estoque' => 300,
        ];
    }

    private function dadosInvalidos(): array
    {
        return [
            'ticker' => 'petr4',
            'preco' => 0,
        ];
    }

    /** R1 (2,0) — listagem pública com o vendedor carregado por eager loading. */
    public function test_r1_listagem_publica_de_ativos(): void
    {
        DB::enableQueryLog();
        $resposta = $this->get('/ativos');
        $consultas = count(DB::getQueryLog());

        $resposta->assertOk();
        $this->assertGreaterThan(0, Ativo::count(), 'O seeder não populou ativos: a migration é compatível com ele?');
        foreach (Ativo::with('vendedor')->get() as $ativo) {
            $resposta->assertSee($ativo->ticker);
            $resposta->assertSee($ativo->vendedor->name);
        }
        $this->assertLessThanOrEqual(3, $consultas, "A listagem fez {$consultas} consultas: falta with('vendedor')?");
    }

    /** R2 (1,5) — login com Auth::attempt e logout. */
    public function test_r2_login_e_logout(): void
    {
        $this->post('/login', ['email' => 'carla@broker.test', 'password' => 'errada'])
            ->assertSessionHasErrors();
        $this->assertGuest();

        $this->post('/login', ['email' => 'carla@broker.test', 'password' => 'senha123'])
            ->assertRedirect();
        $this->assertAuthenticatedAs($this->cliente());

        $this->post('/logout')->assertRedirect();
        $this->assertGuest();
    }

    /** R3 (1,0) — visitante é redirecionado ao login. */
    public function test_r3_rotas_protegidas_por_auth(): void
    {
        foreach (['/ativos/create', '/compras', '/compras/create'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
        $this->post('/ativos', $this->dadosValidos())->assertRedirect('/login');
        $this->post('/compras', ['itens' => []])->assertRedirect('/login');
    }

    /** R4 (2,0) — middleware 'vendedor': cliente recebe 403. */
    public function test_r4_somente_vendedor_cadastra(): void
    {
        $this->actingAs($this->cliente())->get('/ativos/create')->assertForbidden();
        $this->actingAs($this->cliente())->post('/ativos', $this->dadosValidos())->assertForbidden();
        $this->actingAs($this->vendedor())->get('/ativos/create')->assertOk();
    }

    /** R5 (2,0) — cadastro com FormRequest e a regra própria da versão. */
    public function test_r5_cadastro_validado(): void
    {
        $this->actingAs($this->vendedor());

        $this->post('/ativos', [])
            ->assertSessionHasErrors([
                'ticker', 'empresa', 'setor', 'preco', 'estoque',
            ]);

        $this->post('/ativos', array_merge($this->dadosValidos(), $this->dadosInvalidos()))
            ->assertSessionHasErrors(array_keys($this->dadosInvalidos()));

        $this->post('/ativos', $this->dadosValidos())
            ->assertSessionHasNoErrors()
            ->assertRedirect();
        $this->assertDatabaseHas('ativos', ['ticker' => $this->dadosValidos()['ticker']]);
    }

    /** R6 (1,0) — o produto é do vendedor logado, nunca de um campo do formulário. */
    public function test_r6_vendedor_vem_do_usuario_logado(): void
    {
        $this->actingAs($this->vendedor())
            ->post('/ativos', $this->dadosValidos() + ['vendedor_id' => $this->outroVendedor()->id]);

        $this->assertDatabaseHas('ativos', [
            'ticker' => $this->dadosValidos()['ticker'],
            'vendedor_id' => $this->vendedor()->id,
        ]);
    }

    /** R7 (2,5) — compra de um ou mais produtos, com quantidade e preço na pivô. */
    public function test_r7_compra_com_varios_produtos(): void
    {
        [$a, $b, $c] = Ativo::orderBy('id')->take(3)->get();
        $cliente = $this->cliente();

        $this->actingAs($cliente)
            ->post('/compras', ['itens' => [$a->id => 2, $b->id => 1, $c->id => 0]])
            ->assertRedirect();

        $venda = DB::table('vendas')->where('cliente_id', $cliente->id)->first();
        $this->assertNotNull($venda, 'Nenhuma venda foi gravada para o cliente logado.');

        $this->assertDatabaseHas('ativo_venda', [
            'venda_id' => $venda->id, 'ativo_id' => $a->id, 'quantidade' => 2, 'preco_unitario' => $a->preco,
        ]);
        $this->assertDatabaseHas('ativo_venda', [
            'venda_id' => $venda->id, 'ativo_id' => $b->id, 'quantidade' => 1, 'preco_unitario' => $b->preco,
        ]);
        $this->assertDatabaseMissing('ativo_venda', ['venda_id' => $venda->id, 'ativo_id' => $c->id]);
    }

    /** R8 (2,0) — "Minhas compras" mostra só as compras do usuário logado, com total. */
    public function test_r8_minhas_compras(): void
    {
        $produto = Ativo::orderBy('id')->first();
        $agora = now();

        $minha = DB::table('vendas')->insertGetId(['cliente_id' => $this->cliente()->id, 'created_at' => $agora, 'updated_at' => $agora]);
        $alheia = DB::table('vendas')->insertGetId(['cliente_id' => $this->outroCliente()->id, 'created_at' => $agora, 'updated_at' => $agora]);

        DB::table('ativo_venda')->insert([
            ['venda_id' => $minha, 'ativo_id' => $produto->id, 'quantidade' => 3, 'preco_unitario' => 10.50, 'created_at' => $agora, 'updated_at' => $agora],
            ['venda_id' => $alheia, 'ativo_id' => $produto->id, 'quantidade' => 1, 'preco_unitario' => 99.99, 'created_at' => $agora, 'updated_at' => $agora],
        ]);

        $this->actingAs($this->cliente())->get('/compras')
            ->assertOk()
            ->assertSee("Ordem #{$minha}")
            ->assertSee('31,50')
            ->assertDontSee("Ordem #{$alheia}")
            ->assertDontSee('99,99');
    }

    /** Bônus (2,0) — não vende acima do estoque e dá baixa no estoque. */
    public function test_bonus_estoque(): void
    {
        $produto = Ativo::orderBy('id')->first();
        $estoque = $produto->estoque;
        $this->actingAs($this->cliente());

        $this->post('/compras', ['itens' => [$produto->id => $estoque + 1]])->assertSessionHasErrors();
        $this->assertSame($estoque, (int) $produto->fresh()->estoque);
        $this->assertDatabaseCount('vendas', 0);

        $this->post('/compras', ['itens' => [$produto->id => 1]]);
        $this->assertSame($estoque - 1, (int) $produto->fresh()->estoque);
    }
}
