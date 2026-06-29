<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('item_vendas', function (Blueprint $table) {
            $table->dropForeign(['venda_id']);
            $table->foreign('venda_id')
                  ->references('id')
                  ->on('vendas')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('item_vendas', function (Blueprint $table) {
            $table->dropForeign(['venda_id']);
            $table->foreign('venda_id')
                  ->references('id')
                  ->on('vendas');
        });
    }
};
