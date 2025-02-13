<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Controll;
use App\Models\Supplier;
use App\Models\User;

class ControllSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Controll::factory(5)->create();
    }
}
