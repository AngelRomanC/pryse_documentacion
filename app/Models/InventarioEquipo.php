<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioEquipo extends Model
{
    //
    protected $fillable = [
        'fecha_registro',
        'nombre_persona',
        'area',
        'tipo_pc',
        'marca_equipo',
        'sistema_operativo',
        'procesador',
        'tarjeta_madre',
        'tarjeta_grafica',
        'datos_tarjeta_grafica',
        'tipo_ram',
        'capacidad_ram',
        'marca_ram',
        'tipo_disco',
        'capacidad_disco',
        'teclado_mouse',
        'camara_web',
        'otro_periferico',
        'nombre_arqueo',
        'observaciones',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class); //Cargar relación
    }
}
