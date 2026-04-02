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
        if (!Schema::hasColumn('tranzakcio', 'tipus')) {
            Schema::table('tranzakcio', function (Blueprint $table) {
                $table->string('tipus', 20)->default('koltseg');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('tranzakcio', 'tipus')) {
            Schema::table('tranzakcio', function (Blueprint $table) {
                $table->dropColumn('tipus');
            });
        }
    }
};
