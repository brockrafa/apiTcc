<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lancamentos_financeiros', function (Blueprint $table) {
            // Tipo do lançamento: entrada (contas a receber) ou saída (contas a pagar)
            // Adicionar após coluna existente 'status', ajuste conforme seu schema
            $table->enum('tipo', ['entrada', 'saida'])->default('entrada')->after('id');

            // Descrição para lançamentos de saída (contas a receber usam cliente/venda)
            $table->string('descricao')->nullable()->after('tipo');
            $table->string('fornecedor')->nullable()->after('descricao');
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete()->after('fornecedor');
            $table->string('forma_pagamento')->nullable()->after('categoria_id');

            // Vínculo com a conta a pagar que originou o lançamento
            $table->foreignId('conta_pagar_id')->nullable()->constrained('despesas_recorrentes')->nullOnDelete()->after('forma_pagamento');

            $table->text('observacao')->nullable()->after('conta_pagar_id');

            $table->unsignedBigInteger("venda_id")->nullable()->change();
            $table->unsignedBigInteger("cliente_id")->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('lancamentos_financeiros', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropForeign(['conta_pagar_id']);
            $table->dropColumn(['tipo', 'descricao', 'fornecedor', 'categoria_id', 'forma_pagamento', 'conta_pagar_id', 'observacao']);
        });
    }
};