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
        Schema::table('comisions', function (Blueprint $table) {
             $table->foreignId('lider_id')
              ->nullable()
              ->after('ciclo_comision')
              ->constrained('users')
              ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comisions', function (Blueprint $table) {
             Schema::table('comisions', function (Blueprint $table) {
        $table->dropConstrainedForeignId('lider_id');
    });
        });
    }
};
