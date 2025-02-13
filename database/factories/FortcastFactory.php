<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Fortcast;
use App\Models\Material;
use App\Models\ControlPo;
use App\Models\MaterialDetail;
use App\Models\ActualReceive;

class FortcastFactory extends Factory
{
    protected $model = Fortcast::class;

    public function definition(): array
    {
        // Ambil material yang sudah ada
        $material = Material::inRandomOrder()->first();
        if (!$material) {
            $material = Material::factory()->create();
        }

        // Ambil control PO yang sesuai dengan material
        $controlPo = ControlPo::where('material_id', $material->id)
                            ->inRandomOrder()
                            ->first();
        if (!$controlPo) {
            $controlPo = ControlPo::factory()->create([
                'material_id' => $material->id
            ]);
        }

        // Ambil material detail
        $materialDetail = MaterialDetail::where('material_id', $material->id)->inRandomOrder()->first();
        if (!$materialDetail) {
            $materialDetail = MaterialDetail::factory()->create(['material_id' => $material->id]);
        }

        // Ambil actual receive
        $actualReceive = ActualReceive::where('material_id', $material->id)->inRandomOrder()->first();
        if (!$actualReceive) {
            $actualReceive = ActualReceive::factory()->create(['material_id' => $material->id]);
        }

        // 30% kemungkinan PO akan null
        $hasPo = fake()->boolean(70);

        return [
            'material_id' => $material->id,
            'material_detail_id' => $materialDetail->id,
            'po' => $hasPo ? fake()->bothify('PO-####') : null,
            'control_po_id' => $controlPo->id,
            'actual_receive_id' => $actualReceive->id,
            'balance' => $hasPo ? fake()->numberBetween(0, 1000) : '-',
            'persentase' => $hasPo ? fake()->numberBetween(0, 100) . '%' : '-',
            'kanban' => $hasPo ? fake()->numberBetween(1, 50) : '-'
        ];
    }
} 