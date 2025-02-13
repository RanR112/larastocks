<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MaterialDetail;
use App\Models\Material;

class MaterialDetailFactory extends Factory
{
    protected $model = MaterialDetail::class;

    public function definition(): array
    {
        return [
            'material_id' => Material::factory(),
            'diameter' => fake()->randomElement(['1.0', '2.0', '3.0', '4.0', '5.0']),
            'kg_coil' => fake()->randomElement(['500', '750', '1000', '1250', '1500'])
        ];
    }
} 