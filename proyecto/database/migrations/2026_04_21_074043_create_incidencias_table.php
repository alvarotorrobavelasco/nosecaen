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
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->string('persona_contacto', 100);
            $table->string('telefono_contacto', 20);
            $table->text('descripcion');
            $table->string('email_contacto', 150);
            $table->string('direccion', 255)->nullable();
            $table->string('poblacion', 100)->nullable();
            $table->string('codigo_postal', 5)->nullable();
            $table->string('provincia_codigo', 2)->nullable();
            $table->enum('estado', ['P', 'R', 'C'])->default('P');
            $table->foreignId('operario_id')->nullable()->constrained('empleados')->nullOnDelete();
            $table->date('fecha_realizacion')->nullable();
            $table->text('anotaciones_antes')->nullable();
            $table->text('anotaciones_despues')->nullable();
            $table->string('fichero_resumen', 255)->nullable(); // 👈 UNIFICADO AL NOMBRE DEL CÓDIGO
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};