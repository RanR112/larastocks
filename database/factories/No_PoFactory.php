<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\No_Po;

class No_PoFactory extends Factory
{
    protected $model = No_Po::class;

    public function definition(): array
    {
        return [
            'po_date' => fake()->date(),
            'po_name' => fake()->name(),
        ];
    }
}
