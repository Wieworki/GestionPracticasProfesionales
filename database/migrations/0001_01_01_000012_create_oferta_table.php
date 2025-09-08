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
        Schema::create('oferta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained(table: 'empresa')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion');
            $table->date('fecha_creacion');
            $table->date('fecha_cierre');
            $table->string('estado');
            $table->string('modalidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oferta');
    }
};
