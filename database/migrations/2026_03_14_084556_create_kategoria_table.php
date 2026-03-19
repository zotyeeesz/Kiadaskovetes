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
        Schema::create('kategoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('felhasznaloid')->nullable()->constrained('felhasznalo')->onDelete('restrict')->onUpdate('restrict');
            $table->string('nev', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategoria');
    }
};
