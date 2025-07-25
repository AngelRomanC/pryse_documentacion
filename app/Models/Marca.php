<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;


    protected $fillable = ['nombre'];

    public function tipos()
    {
        return $this->hasMany(TipoMarca::class);
    }
}
