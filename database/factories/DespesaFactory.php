<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use app\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DespesaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'descricao' => $this->faker->sentence(),
            'data_ocorrencia' => now(),
            'valor' => $this->faker->randomNumber(2),
            'user_id' => User::pluck('id')->random(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
