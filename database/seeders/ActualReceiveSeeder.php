<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActualReceive;
use App\Models\ControlPo;
use App\Models\MaterialDetail;

class ActualReceiveSeeder extends Seeder
{
    public function run(): void
    {
        $controlPos = ControlPo::all();

        foreach ($controlPos as $controlPo) {
            // Pastikan ada material detail untuk material ini
            $materialDetail = MaterialDetail::where('material_id', $controlPo->material_id)->first();
            if (!$materialDetail) continue;

            // Buat 1-3 actual receive untuk setiap control PO
            $numberOfReceives = rand(1, 3);
            
            for ($i = 0; $i < $numberOfReceives; $i++) {
                ActualReceive::factory()->create([
                    'supplier_id' => $controlPo->supplier_id,
                    'material_id' => $controlPo->material_id,
                    'no_po_id' => $controlPo->no_po_id,
                    'control_po_id' => $controlPo->id,
                ]);
            }
        }
    }
} 