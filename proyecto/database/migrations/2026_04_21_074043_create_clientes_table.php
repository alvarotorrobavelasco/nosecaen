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
    Schema::create('clientes', function (Blueprint $table) {
        $table->id();
        $table->string('cif', 9)->unique();
        $table->string('nombre', 100);
        $table->string('telefono', 20)->nullable();
        $table->string('email', 150)->nullable();
        $table->string('cuenta_corriente', 34)->nullable();
        $table->string('pais', 50);
        $table->string('moneda', 3)->default('EUR');
        $table->decimal('cuota_mensual', 10, 2)->default(0.00);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
