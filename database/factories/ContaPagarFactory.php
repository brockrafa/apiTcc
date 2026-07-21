<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\ContaPagar;
use App\Models\DespesaRecorrente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContaPagarFactory extends Factory
{
    protected $model = ContaPagar::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['pendente', 'pago', 'atrasado']);
        $valor = $this->faker->randomFloat(2, 80, 4000);

        return [
            'despesa_recorrente_id' => DespesaRecorrente::query()->inRandomOrder()->value('id') ?? null,
            'categoria_id' => Categoria::query()->inRandomOrder()->value('id') ?? 1,
            'descricao' => $this->faker->randomElement([
                'Pagamento mensal',
                'Conta de fornecedor',
                'Despesa operacional',
                'Serviço externo',
            ]),
            'fornecedor' => $this->faker->company(),
            'valor' => $valor,
            'data_vencimento' => $this->faker->dateTimeBetween('-1 month', '+2 month')->format('Y-m-d'),
            'tipo' => $this->faker->randomElement(['fixa', 'variavel', 'parcelada', 'eventual']),
            'numero_parcela' => $this->faker->numberBetween(1, 3),
            'total_parcelas' => $this->faker->numberBetween(1, 3),
            'observacao' => $this->faker->sentence(),
            'status' => $status,
            'valor_pago' => $status === 'pago' ? $valor : null,
            'data_pagamento' => $status === 'pago' ? $this->faker->dateTimeBetween('-15 days', 'now')->format('Y-m-d') : null,
            'forma_pagamento' => $status === 'pago' ? $this->faker->randomElement(['pix', 'dinheiro', 'cartao', 'boleto']) : null,
            'observacao_pagamento' => $status === 'pago' ? $this->faker->sentence() : null,
        ];
    }
}
