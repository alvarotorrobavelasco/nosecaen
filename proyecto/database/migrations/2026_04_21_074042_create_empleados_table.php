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
    Schema::create('empleados', function (Blueprint $table) {
        $table->id();
        $table->string('dni', 9)->unique();
        $table->string('nombre', 100);
        $table->string('email', 150)->unique();
        $table->string('telefono', 20)->nullable();
        $table->string('direccion', 255)->nullable();
        $table->date('fecha_alta');
        $table->enum('tipo', ['administrador', 'operario'])->default('operario');
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
