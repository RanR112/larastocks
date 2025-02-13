<?php

namespace Database\Factories;

use App\Models\ActualReceive;
use App\Models\Supplier;
use App\Models\Material;
use App\Models\No_Po;
use App\Models\ControlPo;
use App\Models\MaterialDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActualReceiveFactory extends Factory
{
    protected $model = ActualReceive::class;

    public function definition(): array
    {
        // Ambil Control PO yang sudah ada
        $controlPo = ControlPo::inRandomOrder()->first();
        
        if (!$controlPo) {
            // Jika tidak ada Control PO, buat baru
            $controlPo = ControlPo::factory()->create();
        }

        return [
            'in_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'supplier_id' => $controlPo->supplier_id,
            'material_id' => $controlPo->material_id,
            'material_detail_id' => $controlPo->material_detail_id,
            'no_po_id' => $controlPo->no_po_id,
            'delivery_number' => 'DN-' . $this->faker->unique()->numberBetween(1000, 9999),
            'weight' => (string)$this->faker->numberBetween(100, 1000),
            'total_coil' => (string)$this->faker->numberBetween(1, 10),
            'control_po_id' => $controlPo->id,
            'charge_number' => 'CH-' . $this->faker->unique()->numberBetween(1000, 9999),
            'coil_no' => 'CN-' . $this->faker->unique()->numberBetween(1000, 9999),
            'note' => $this->faker->sentence()
        ];
    }
} 