<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FormaPagamento;

class FormaPagamentoSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FormaPagamento::create([
            'descricao' => 'Dinheiro'
        ]);

        FormaPagamento::create([
            'descricao' => 'Pix'
        ]);

        FormaPagamento::create([
            'descricao' => 'Débito'
        ]);


        FormaPagamento::create([
            'descricao' => 'Crédito'
        ]);
    }
}
