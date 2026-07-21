<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'telefone' => $this->faker->phoneNumber(),
            'cep' => $this->faker->postcode(),
            'bairro' => $this->faker->streetName(),
            'logradouro' => $this->faker->streetAddress(),
            'cidade' => $this->faker->city(),
        ];
    }
}
