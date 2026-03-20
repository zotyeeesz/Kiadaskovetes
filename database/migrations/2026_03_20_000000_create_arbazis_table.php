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
        Schema::create('arbazis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penznemid')->constrained('penznem')->onDelete('cascade')->onUpdate('cascade');
            $table->double('arfolyam'); // 1 egység az adott pénznemből = X forint
            $table->timestamps();
            $table->unique('penznemid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arbazis');
    }
};
