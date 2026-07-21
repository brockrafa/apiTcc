<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['clientes', 'produtos', 'servicos', 'categorias', 'vendas', 'item_vendas', 'lancamentos_financeiros', 'despesas_recorrentes', 'contas_pagar'];

        foreach ($tables as $table) {
            if (!Schema::hasColumn($table, 'empresa_id')) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->nullOnDelete();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = ['clientes', 'produtos', 'servicos', 'categorias', 'vendas', 'item_vendas', 'lancamentos_financeiros', 'despesas_recorrentes', 'contas_pagar'];

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'empresa_id')) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->dropConstrainedForeignId('empresa_id');
                });
            }
        }
    }
};
