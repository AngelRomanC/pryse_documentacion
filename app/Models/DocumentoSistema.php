<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoSistema extends Model
{
    use HasFactory;
    protected $fillable = [
        'documento_id',
        'ruta_archivo',
        'nombre_original',       
    ];

    public function sistema() {
        return $this->belongsTo(Sistema::class);
    }
}
