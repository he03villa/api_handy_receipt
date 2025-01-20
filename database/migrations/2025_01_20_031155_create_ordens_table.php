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
        Schema::create('ordens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->nullable()->index();
            $table->foreignId('vendedor_id')->nullable()->index();
            $table->foreignId('cliente_id')->nullable()->index();
            $table->foreignId('tipo_ordens_id')->nullable()->index();
            $table->enum('status', ['pendiente', 'entregado', 'cancelado', 'pagado'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordens');
    }
};
