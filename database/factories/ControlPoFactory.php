<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ControlPo;
use App\Models\Supplier;
use App\Models\Material;
use App\Models\No_Po;
use App\Models\MaterialDetail;

class ControlPoFactory extends Factory
{
    protected $model = ControlPo::class;

    public function definition(): array
    {
        $supplier = Supplier::inRandomOrder()->first();
        $material = Material::where('supplier_id', $supplier->id)->inRandomOrder()->first();
        if (!$material) {
            $material = Material::factory()->create(['supplier_id' => $supplier->id]);
        }

        // Ambil material detail
        $materialDetail = MaterialDetail::where('material_id', $material->id)->inRandomOrder()->first();
        if (!$materialDetail) {
            $materialDetail = MaterialDetail::factory()->create(['material_id' => $material->id]);
        }

        $noPo = No_Po::inRandomOrder()->first();

        return [
            'supplier_id' => $supplier->id,
            'material_id' => $material->id,
            'material_detail_id' => $materialDetail->id,
            'no_po_id' => $noPo->id,
            'schedule_incoming' => $this->faker->dateTimeThisYear(),
            'qty_coil' => (string)$this->faker->numberBetween(1, 10),
            'qty_kg' => (string)$this->faker->numberBetween(500, 5000),
            'month' => $this->faker->monthName(),
            'material_receiving_status' => 'waiting'
        ];
    }
} 