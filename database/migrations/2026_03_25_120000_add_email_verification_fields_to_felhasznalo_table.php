<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('felhasznalo', function (Blueprint $table) {
            if (!Schema::hasColumn('felhasznalo', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
            if (!Schema::hasColumn('felhasznalo', 'verification_token')) {
                $table->string('verification_token', 100)->nullable();
            }
            if (!Schema::hasColumn('felhasznalo', 'verification_sent_at')) {
                $table->timestamp('verification_sent_at')->nullable();
            }
        });

        // A már meglévő felhasználókat tekintsük megerősítettnek.
        DB::table('felhasznalo')
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('felhasznalo', function (Blueprint $table) {
            if (Schema::hasColumn('felhasznalo', 'verification_sent_at')) {
                $table->dropColumn('verification_sent_at');
            }
            if (Schema::hasColumn('felhasznalo', 'verification_token')) {
                $table->dropColumn('verification_token');
            }
            if (Schema::hasColumn('felhasznalo', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
        });
    }
};
