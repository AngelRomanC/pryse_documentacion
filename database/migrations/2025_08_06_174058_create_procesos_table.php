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
        Schema::create('procesos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('departamento_id');
            $table->string('fechac_inicio');
            $table->string('fechac_entrega');
            $table->string('fechac_valdacion');
            $table->string('fechac_vigencia');
            $table->string('estatus');
            $table->string('numero_usuarios');
            $table->string('nombre_creador');
            $table->string('nombre_autorizacion');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procesos');
    }
};
