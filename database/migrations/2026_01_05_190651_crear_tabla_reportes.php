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
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('tipo_problema_id')
                ->constrained('tipos_problema')
                ->onDelete('restrict');

            $table->foreignId('estado_id')
                ->constrained('estados_reporte')
                ->onDelete('restrict');

            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);

            $table->string('direccion')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
