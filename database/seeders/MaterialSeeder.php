<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\MaterialDetail;
use App\Models\Supplier;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua supplier yang ada
        $suppliers = Supplier::all();

        foreach ($suppliers as $supplier) {
            // Buat 2 material untuk setiap supplier
            Material::factory(2)->create([
                'supplier_id' => $supplier->id
            ])->each(function ($material) {
                // Buat 2-4 detail untuk setiap material
                $details = [];
                $numberOfDetails = rand(2, 4);
                
                for ($i = 0; $i < $numberOfDetails; $i++) {
                    MaterialDetail::factory()->create([
                        'material_id' => $material->id
                    ]);
                }
            });
        }
    }
}
