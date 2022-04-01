<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Paciente;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PacienteFactory extends Factory
{
    protected $model = Paciente::class;
    
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
            'telefone' => $this->faker->numerify('(##) #####-####'),
            'nascimento' => $this->faker->dateTimeInInterval($startDate = '-50 year', $interval = '-18 years', $timezone = null)->format('Y-m-d'),
            'sexo' => $this->faker->randomElement(['masculino', 'feminino']),
            'tipo_sanguineo' => $this->faker->randomElement(['A+', 'B+', 'AB+', 'O+', 'A-', 'B-', 'AB-', 'O-']),
            'peso' => $this->faker->numberBetween(50, 90),
            'altura' => ($this->faker->numberBetween(130, 200) / 100)
        ];
    }
}
