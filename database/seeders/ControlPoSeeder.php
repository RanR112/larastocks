<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ControlPo;
use App\Models\Supplier;

class ControlPoSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = Supplier::all();

        foreach ($suppliers as $supplier) {
            // Buat 3-5 control PO untuk setiap supplier
            $numberOfPos = rand(3, 5);
            
            ControlPo::factory($numberOfPos)->create([
                'supplier_id' => $supplier->id
            ]);
        }
    }
} 