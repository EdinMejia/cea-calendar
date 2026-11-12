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
        Schema::create('acceso_bitacoras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();  // sin llave foránea: la bitácora sobrevive si se borra el usuario
            $table->string('usuario', 120);
            $table->string('correo', 191);
            $table->string('accion', 20);
            $table->string('detalle')->nullable();
            $table->string('realizado_por', 120);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acceso_bitacoras');
    }
};
