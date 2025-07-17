<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sistema extends Model {
    protected $fillable = [
        'nombre',
        'descripcion',
        'departamento_id',
        'url',
        'fecha_creacion',
        'fecha_produccion',
        'estatus',
        'numero_usuarios',
        'nombre_servidor',
        'ip_servidor',
        'sistema_operativo',
        'nombre_servidor_bd',
        'ip_servidor_bd',
        'lenguaje_desarrollo',
        'version_lenguaje',
    ];
        
    public function departamento() {
        return $this->belongsTo(Departamento::class); //falto
    }

    //relación con archivosPDF
    public function archivos() {
        return $this->hasMany(DocumentoSistema::class);
    }
}
