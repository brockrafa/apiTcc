<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\LancamentoFinanceiro;
use App\Models\Venda;
use Illuminate\Database\Eloquent\Factories\Factory;

class LancamentoFinanceiroFactory extends Factory
{
    protected $model = LancamentoFinanceiro::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['pendente', 'pago', 'atrasado']);
        $valor = $this->faker->randomFloat(2, 50, 5000);
        $venda = Venda::query()->inRandomOrder()->first();
        $cliente = Cliente::query()->inRandomOrder()->first();

        return [
            'venda_id' => $venda?->id ?? 1,
            'cliente_id' => $cliente?->id ?? 1,
            'numero_parcela' => $this->faker->numberBetween(1, 4),
            'total_parcelas' => $this->faker->numberBetween(1, 4),
            'valor' => $valor,
            'valor_pago' => $status === 'pago' ? $valor : 0,
            'data_vencimento' => $this->faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'data_pagamento' => $status === 'pago' ? $this->faker->dateTimeBetween('-15 days', 'now')->format('Y-m-d') : null,
            'status' => $status,
            'tipo' => 'entrada',
            'descricao' => 'Recebimento de venda',
            'fornecedor' => null,
            'categoria_id' => Categoria::query()->inRandomOrder()->value('id') ?? 1,
            'forma_pagamento' => $this->faker->randomElement(['pix', 'dinheiro', 'cartao', 'boleto']),
            'conta_pagar_id' => null,
            'observacao' => $this->faker->sentence(),
        ];
    }
}
