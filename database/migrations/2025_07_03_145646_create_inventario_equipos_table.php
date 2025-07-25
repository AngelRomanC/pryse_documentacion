<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventario_equipos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_registro');
            $table->string('nombre_persona');
            $table->string('departamento_id');
            $table->string('tipo_pc');
            $table->string('marca_equipo');
            $table->string('sistema_operativo');
            $table->string('procesador');
            $table->string('tarjeta_madre');
            $table->string('tarjeta_grafica')->nullable();
            $table->string('datos_tarjeta_grafica')->nullable()->default('N/A');
            $table->string('tipo_ram');
            $table->string('capacidad_ram');
            $table->string('marca_ram');
            $table->string('tipo_disco');
            $table->string('capacidad_disco');
            $table->string('teclado_mouse');
            $table->string('camara_web')->nullable();
            $table->string('otro_periferico')->nullable();
            $table->string('software_remoto')->nullable();
            $table->string('id_remoto')->nullable();
            $table->string('password_remoto')->nullable();
            $table->string('name_id');
            $table->string('observaciones')->nullable()->default('N/A');
            ;

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_equipos');
    }
};
