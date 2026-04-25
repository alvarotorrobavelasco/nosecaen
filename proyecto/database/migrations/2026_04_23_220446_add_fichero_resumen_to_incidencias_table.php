<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incidencias', function (Blueprint $table) {
            // Campo para guardar la ruta del archivo (ej: ficheros-incidencias/archivo.pdf)
            $table->string('fichero_resumen')->nullable()->after('anotaciones_despues');
        });
    }

    public function down(): void
    {
        Schema::table('incidencias', function (Blueprint $table) {
            $table->dropColumn('fichero_resumen');
        });
    }
};