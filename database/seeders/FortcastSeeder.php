<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fortcast;
use App\Models\ControlPo;
use App\Models\ActualReceive;

class FortcastSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua control PO yang ada
        $controlPos = ControlPo::all();

        foreach ($controlPos as $controlPo) {
            // Ambil atau buat actual receive yang sesuai
            $actualReceive = ActualReceive::where('control_po_id', $controlPo->id)->first();
            
            if (!$actualReceive) {
                $actualReceive = ActualReceive::factory()->create([
                    'material_id' => $controlPo->material_id,
                    'control_po_id' => $controlPo->id,
                    'supplier_id' => $controlPo->supplier_id,
                    'material_detail_id' => $controlPo->material_detail_id,
                    'no_po_id' => $controlPo->no_po_id
                ]);
            }

            // Buat 1-2 fortcast untuk setiap control PO
            $numberOfFortcasts = rand(1, 2);
            
            for ($i = 0; $i < $numberOfFortcasts; $i++) {
                Fortcast::factory()->create([
                    'material_id' => $controlPo->material_id,
                    'material_detail_id' => $controlPo->material_detail_id,
                    'control_po_id' => $controlPo->id,
                    'actual_receive_id' => $actualReceive->id
                ]);
            }
        }
    }
} 