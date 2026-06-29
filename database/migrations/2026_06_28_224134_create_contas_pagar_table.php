<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contas_pagar', function (Blueprint $table) {
            $table->id();

            // Vínculo opcional com recorrente (null = lançamento manual)
            $table->foreignId('despesa_recorrente_id')
                  ->nullable()
                  ->constrained('despesas_recorrentes')
                  ->nullOnDelete();

            $table->foreignId('categoria_id')
                  ->nullable()
                  ->constrained('categorias')
                  ->nullOnDelete();

            $table->string('descricao');
            $table->string('fornecedor')->nullable();
            $table->decimal('valor', 10, 2);
            $table->date('data_vencimento');
            $table->enum('tipo', ['fixa', 'variavel', 'parcelada', 'eventual'])->default('fixa');
            $table->unsignedSmallInteger('numero_parcela')->default(1);
            $table->unsignedSmallInteger('total_parcelas')->default(1);
            $table->text('observacao')->nullable();

            // Dados de pagamento — preenchidos ao marcar como pago
            $table->enum('status', ['pendente', 'pago', 'atrasado'])->default('pendente');
            $table->decimal('valor_pago', 10, 2)->nullable();
            $table->date('data_pagamento')->nullable();
            $table->string('forma_pagamento')->nullable(); // pix, dinheiro, etc.
            $table->text('observacao_pagamento')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contas_pagar');
    }
};