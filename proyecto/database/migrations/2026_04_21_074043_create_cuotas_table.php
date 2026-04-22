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
    Schema::create('cuotas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
        $table->string('concepto', 150);
        $table->date('fecha_emision');
        $table->decimal('importe', 10, 2);
        $table->enum('pagada', ['S', 'N'])->default('N');
        $table->date('fecha_pago')->nullable();
        $table->text('notas')->nullable();
        $table->string('archivo_pdf', 255)->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};
