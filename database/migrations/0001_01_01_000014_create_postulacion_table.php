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
        Schema::create('postulacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oferta_id')->constrained(table: 'oferta')->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained(table: 'estudiante')->onDelete('cascade');
            $table->date('fecha_creacion');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postulacion');
    }
};
