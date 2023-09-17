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
      Schema::create('citas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('cliente_id');
        $table->unsignedBigInteger('usuario_id');
        $table->dateTime('fecha_inicio');
        $table->dateTime('fecha_fin');
        $table->enum('estado', ['planificado', 'en proceso', 'ejecutada'])->default('planificado');
        $table->string('ubicacion');
        $table->text('diagnostico')->nullable();
        $table->text('practicas_a_desarrollar')->nullable();
        $table->timestamps();

        $table->foreign('cliente_id')->references('id')->on('clientes');
        $table->foreign('usuario_id')->references('id')->on('users');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
