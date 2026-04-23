<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints(); // Desactiva restricciones temporalmente

        Schema::table('incidencias', function (Blueprint $table) {
            $table->unsignedBigInteger('cliente_id')->nullable()->change();
        });

        Schema::enableForeignKeyConstraints(); // Vuelve a activar restricciones
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('incidencias', function (Blueprint $table) {
            $table->unsignedBigInteger('cliente_id')->nullable(false)->change();
        });

        Schema::enableForeignKeyConstraints();
    }
};