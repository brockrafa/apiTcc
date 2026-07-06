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
        Schema::table('item_vendas', function (Blueprint $table) {
            $table->unsignedBigInteger('servico_id')->nullable()->after('produto_id');
            $table->string('tipo')->after('servico_id');
            $table->unsignedBigInteger("produto_id")->nullable()->change();
            $table->foreign('servico_id')
                  ->references('id')
                  ->on('servicos')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('item_vendas', function (Blueprint $table) {
            $table->dropForeign(['servico_id']);
            $table->dropColumn('tipo');
            $table->dropColumn('servico_id');
        });
    }
};
