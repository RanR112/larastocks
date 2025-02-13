<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Issuelot;
use App\Models\Material;

class IssuelotSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua material yang memiliki detail
        $materials = Material::whereHas('details')->get();

        foreach ($materials as $material) {
            // Buat 2-4 issuelot untuk setiap material
            $numberOfIssues = rand(2, 4);
            
            for ($i = 0; $i < $numberOfIssues; $i++) {
                Issuelot::factory()->create([
                    'supplier_id' => $material->supplier_id,
                    'material_id' => $material->id,
                    'material_detail_id' => $material->details->random()->id
                ]);
            }
        }
    }
} 