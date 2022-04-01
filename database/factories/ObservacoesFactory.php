<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Observacoes;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ObservacoesFactory extends Factory
{
    protected $model = Observacoes::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'consulta_id' => null,
            'mensagem' => 'lorem ipsum dolor sit amet'
        ];
    }
}
