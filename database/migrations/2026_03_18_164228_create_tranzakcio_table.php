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
        Schema::create('tranzakcio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('felhasznaloid')->constrained('felhasznalo')->onDelete('restrict')->onUpdate('restrict');
            $table->foreignId('kategoriaid')->constrained('kategoria')->onDelete('restrict')->onUpdate('restrict');
            $table->date('rogzites');
            $table->foreignId('penznemid')->constrained('penznem')->onDelete('restrict')->onUpdate('restrict');
            $table->double('osszeg');
            $table->string('megjegyzes', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tranzakcio');
    }
};
