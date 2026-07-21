<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\FormaPagamento;
use App\Models\Venda;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendaFactory extends Factory
{
    protected $model = Venda::class;

    public function definition(): array
    {
        $tipoVenda = $this->faker->randomElement(['avista', 'parcelado', 'prazo']);
        $parcelas = $tipoVenda === 'parcelado' ? $this->faker->numberBetween(2, 6) : 1;
        $valor = $this->faker->randomFloat(2, 100, 5000);

        return [
            'cliente_id' => Cliente::factory(),
            'forma_pagamento_id' => FormaPagamento::query()->inRandomOrder()->value('id') ?? 1,
            'total' => $valor,
            'data_venda' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'tipo_venda' => $tipoVenda,
            'parcelas' => $parcelas,
            'entrada' => $tipoVenda === 'avista' ? 0 : $this->faker->randomFloat(2, 0, $valor / 2),
            'valor_parcela' => $parcelas > 1 ? round($valor / $parcelas, 2) : null,
            'primeiro_vencimento' => $tipoVenda === 'avista' ? null : $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
        ];
    }
}
