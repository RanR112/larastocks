<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Roles;
use App\Models\Supplier;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Roles::where('name', 'staff')->first();
        User::create([
            'name' => 'Staff',
            'email' => 'siapaajah@gmail.com',
            'nik' => '12345678',
            'role_id' => $role->id,
            'password' => Hash::make('dsadsadsa')
        ]);

        $role = Roles::where('name', 'supplier')->first();
        
        // Buat array untuk menyimpan NIK yang sudah digunakan
        $usedNiks = [];
        
        for ($i = 0; $i < 5; $i++) {
            do {
                $nik = fake()->numerify('########');
            } while (in_array($nik, $usedNiks));
            
            $usedNiks[] = $nik;
            
            User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'nik' => $nik,
                'role_id' => $role->id,
                'supplier_id' => Supplier::inRandomOrder()->first()->id,
                'password' => Hash::make('dsadsadsa')
            ]);
        }
    }
}
