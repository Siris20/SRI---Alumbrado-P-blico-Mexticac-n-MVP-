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
    Schema::table('reportes', function (Blueprint $table) {
        $table->string('descripcion', 1000)->default('')->change();
    });
}

public function down(): void
{
    Schema::table('reportes', function (Blueprint $table) {
        $table->string('descripcion', 1000)->default(null)->nullable()->change();
    });
}
};
