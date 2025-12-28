<?php

namespace Database\Seeders;

use App\Models\BoxTemplate;
use Illuminate\Database\Seeder;

class BoxTemplateSeeder extends Seeder
{
    /**
     * Seed box templates (Heavy Meal & Snack Box).
     */
    public function run(): void
    {
        $templates = [
            // Heavy Meal
            [
                'name' => 'Nasi Box A',
                'type' => 'heavy_meal',
                'price' => 25000,
                'items_json' => ['Nasi Putih', 'Ayam Goreng', 'Sambal', 'Lalapan', 'Kerupuk'],
            ],
            [
                'name' => 'Nasi Box B',
                'type' => 'heavy_meal',
                'price' => 30000,
                'items_json' => ['Nasi Putih', 'Rendang', 'Telur Dadar', 'Sambal', 'Lalapan'],
            ],
            [
                'name' => 'Nasi Box Premium',
                'type' => 'heavy_meal',
                'price' => 40000,
                'items_json' => ['Nasi Putih', 'Ayam Bakar', 'Ikan Goreng', 'Sayur Asem', 'Sambal'],
            ],
            // Snack Box
            [
                'name' => 'Snack Box A',
                'type' => 'snack_box',
                'price' => 15000,
                'items_json' => ['Risoles', 'Pastel', 'Kue Sus', 'Air Mineral'],
            ],
            [
                'name' => 'Snack Box B',
                'type' => 'snack_box',
                'price' => 20000,
                'items_json' => ['Lemper', 'Dadar Gulung', 'Bolu Kukus', 'Onde-onde', 'Teh Kotak'],
            ],
            [
                'name' => 'Snack Box Premium',
                'type' => 'snack_box',
                'price' => 30000,
                'items_json' => ['Risoles Mayo', 'Pastel Ayam', 'Kroket', 'Lumpia', 'Puding', 'Jus Buah'],
            ],
        ];

        foreach ($templates as $template) {
            BoxTemplate::create([
                'name' => $template['name'],
                'type' => $template['type'],
                'price' => $template['price'],
                'items_json' => $template['items_json'],
                'is_active' => true,
            ]);
        }
    }
}
