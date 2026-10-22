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
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('lugar', 100);
            $table->dateTime('hora_fecha');
            $table->time('hora_finalizacion');
            $table->tinyInteger('estado')->default(1);
            $table->text('descripcion')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('comisiones_id')->constrained('comisions');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
