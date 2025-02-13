<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\No_Po;

class Po_NoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        No_Po::factory(5)->create();
    }
}
