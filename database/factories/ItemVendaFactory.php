<?php

namespace Database\Factories;

use App\Models\ItemVenda;
use App\Models\Produto;
use App\Models\Servico;
use App\Models\Venda;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemVendaFactory extends Factory
{
    protected $model = ItemVenda::class;

    public function definition(): array
    {
        $useServico = $this->faker->boolean(30);
        $produto = Produto::query()->inRandomOrder()->first();
        $servico = Servico::query()->inRandomOrder()->first();

        return [
            'venda_id' => Venda::factory(),
            'produto_id' => $useServico ? null : ($produto?->id ?? 1),
            'servico_id' => $useServico ? ($servico?->id ?? 1) : null,
            'tipo' => $useServico ? 'servico' : 'produto',
            'quantidade' => $this->faker->numberBetween(1, 5),
            'valor_unitario' => $useServico
                ? ($servico?->valor ?? 150)
                : ($produto?->valor_venda ?? 100),
        ];
    }
}
