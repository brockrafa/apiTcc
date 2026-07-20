<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lancamentos_financeiros', function (Blueprint $table) {
            $table->decimal('valor_pago', 15, 2)->nullable()->after('valor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lancamentos_financeiros', function (Blueprint $table) {
            $table->dropColumn('valor_pago');
        });
    }
};
