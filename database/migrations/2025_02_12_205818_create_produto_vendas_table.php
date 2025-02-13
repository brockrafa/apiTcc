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
        Schema::create('produto_vendas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("venda_id");
            $table->unsignedBigInteger("produto_id");
            $table->integer("quantidade");
            $table->decimal("valor_unitario", 10, 2);
            $table->foreign("venda_id")->references("id")->on("vendas");
            $table->foreign("produto_id")->references("id")->on("produtos");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto_vendas');
    }
};
