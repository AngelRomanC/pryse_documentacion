<?php

namespace Database\Seeders;

use App\Models\Marca;
use App\Models\TipoMarca;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    public function run(): void
    {
        // Catálogo de marcas agrupado por tipo
        $tiposConMarcas = [
            'marca_equipo' => ['GENERICA (Armada)', 'DELL', 'HP', 'LENOVO', 'ASUS', 'VORAGO', 'ACER', 'GHIA', 'HIUNDAI', 'SAMSUNG', 'MSI', 'Otra', 'Sharp', 'Brother'],
            'tarjeta_madre' => ['DELL', 'HP', 'LENOVO', 'ASUS', 'GENÉRICA', 'PROPIA DE LA MARCA'],
            'marca_ram' => ['Kingston', 'Adata', 'G. Skill', 'Corsair', 'Lexa', 'Crucial', 'Otra'],
            'teclado_mouse' => ['Genéricos', 'DELL', 'HP', 'Logitec', 'Microsoft', 'Genius', 'Razer', 'HyperX'],
            'camara_web' => ['Genéricos', 'DELL', 'HP', 'Logitec', 'Microsoft', 'Genius', 'Razer', 'HyperX'],
        ];

        foreach ($tiposConMarcas as $tipo => $marcas) {
            foreach ($marcas as $nombreMarca) {
                // Crear o buscar marca
                $marca = Marca::firstOrCreate(['nombre' => $nombreMarca]);

                // Asignar tipo solo si no existe ya
                TipoMarca::firstOrCreate([
                    'marca_id' => $marca->id,
                    'tipo' => $tipo,
                ]);
            }
        }
    }
}
