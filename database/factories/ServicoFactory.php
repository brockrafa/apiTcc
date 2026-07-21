<?php

namespace Database\Factories;

use App\Models\Servico;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServicoFactory extends Factory
{
    protected $model = Servico::class;

    public function definition(): array
    {
        return [
            'servico' => $this->faker->randomElement([
                'Consultoria',
                'Instalação',
                'Manutenção',
                'Treinamento',
                'Suporte',
                'Configuração',
                'Assessoria',
                'Implantação',
            ]) . ' ' . $this->faker->randomElement(['Básico', 'Premium', 'Especial', 'Express']),
            'valor' => $this->faker->randomFloat(2, 80, 1500),
            'categoria' => $this->faker->randomElement([
                'Tecnologia',
                'Consultoria',
                'Suporte',
                'Gestão',
            ]),
        ];
    }
}
