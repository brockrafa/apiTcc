<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\DespesaRecorrente;
use Illuminate\Database\Eloquent\Factories\Factory;

class DespesaRecorrenteFactory extends Factory
{
    protected $model = DespesaRecorrente::class;

    public function definition(): array
    {
        return [
            'descricao' => $this->faker->randomElement([
                'Internet',
                'Energia',
                'Aluguel',
                'Telefonia',
                'Software',
                'Marketing',
            ]),
            'fornecedor' => $this->faker->company(),
            'categoria_id' => Categoria::query()->inRandomOrder()->value('id') ?? 1,
            'valor' => $this->faker->randomFloat(2, 100, 3000),
            'dia_vencimento' => $this->faker->numberBetween(1, 28),
            'ativa' => $this->faker->boolean(80),
        ];
    }
}
