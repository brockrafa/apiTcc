<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\ContaPagar;
use App\Models\DespesaRecorrente;
use App\Models\FormaPagamento;
use App\Models\ItemVenda;
use App\Models\LancamentoFinanceiro;
use App\Models\Produto;
use App\Models\Servico;
use App\Models\Venda;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        Categoria::factory(10)->create();
        Cliente::factory(50)->create();
        Produto::factory(30)->create();
        Servico::factory(20)->create();

        $formas = FormaPagamento::all();
        if ($formas->isEmpty()) {
            $this->call(FormaPagamentoSeed::class);
        }

        $vendas = Venda::factory(100)->create();

        foreach ($vendas as $venda) {
            $itensPorVenda = random_int(1, 3);

            for ($i = 0; $i < $itensPorVenda; $i++) {
                ItemVenda::factory()->create([
                    'venda_id' => $venda->id,
                ]);
            }

            LancamentoFinanceiro::factory()->create([
                'venda_id' => $venda->id,
                'cliente_id' => $venda->cliente_id,
                'valor' => $venda->total,
                'total_parcelas' => $venda->parcelas ?? 1,
                'numero_parcela' => 1,
            ]);
        }

        DespesaRecorrente::factory(15)->create();
        ContaPagar::factory(30)->create();
    }
}
