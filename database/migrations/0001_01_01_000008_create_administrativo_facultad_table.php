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
        Schema::create('administrativo_facultad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrativo_id')->constrained(table: 'administrativo')->onDelete('cascade');
            $table->foreignId('facultad_id')->constrained(table: 'facultad')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrativo_facultad');
    }
};
