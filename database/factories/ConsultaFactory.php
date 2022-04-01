<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Consulta;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ConsultaFactory extends Factory
{
    protected $model = Consulta::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'medico_id' => null,
            'paciente_id' => null,
            'data' => ($data = $this->faker->dateTimeBetween($startDate = '- 3 days', $interval = '+ 2 days', $timezone = null))->format('Y-m-d H:i:s')
        ];
    }
}
