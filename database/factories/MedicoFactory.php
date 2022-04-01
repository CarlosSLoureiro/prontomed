<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Medico;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class MedicoFactory extends Factory
{
    protected $model = Medico::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nome' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'senha' => Str::random(10),
            'status' => 'ativo'
        ];
    }
}
