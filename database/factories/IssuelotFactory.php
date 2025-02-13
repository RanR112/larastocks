<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Issuelot;
use App\Models\Supplier;
use App\Models\Material;
use App\Models\MaterialDetail;

class IssuelotFactory extends Factory
{
    protected $model = Issuelot::class;

    public function definition(): array
    {
        // Ambil supplier yang memiliki material
        $supplier = Supplier::whereHas('users')->inRandomOrder()->first();
        if (!$supplier) {
            $supplier = Supplier::factory()->create();
        }

        // Ambil atau buat material untuk supplier ini
        $material = Material::where('supplier_id', $supplier->id)->inRandomOrder()->first();
        if (!$material) {
            $material = Material::factory()->create(['supplier_id' => $supplier->id]);
        }

        // Ambil atau buat material detail
        $materialDetail = MaterialDetail::where('material_id', $material->id)->inRandomOrder()->first();
        if (!$materialDetail) {
            $materialDetail = MaterialDetail::factory()->create(['material_id' => $material->id]);
        }

        return [
            'supplier_id' => $supplier->id,
            'material_id' => $material->id,
            'material_detail_id' => $materialDetail->id,
            'tag_lot' => 'TL-' . fake()->unique()->numberBetween(1000, 9999),
            'qa_tag' => 'QA-' . fake()->unique()->numberBetween(1000, 9999),
            'charge_no' => 'CH-' . fake()->unique()->numberBetween(1000, 9999),
            'qty_kg' => fake()->numberBetween(100, 1000),
            'coil_no' => 'CN-' . fake()->unique()->numberBetween(1000, 9999),
            'qr_no' => 'QR-' . fake()->unique()->numberBetween(1000, 9999)
        ];
    }
} 