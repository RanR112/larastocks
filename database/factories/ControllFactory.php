<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Supplier;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Controll>
 */
class ControllFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'pdf_supplier' => 'pdf_supplier.pdf',
            'pdf_fortcast' => 'pdf_fortcast.pdf',
        ];
    }
}
