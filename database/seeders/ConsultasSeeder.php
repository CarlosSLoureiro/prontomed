<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Consulta;
use App\Models\Medico;
use App\Models\Paciente;
use DateTime;

class ConsultasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $consultas = Consulta::factory(40)->make([
            'medico_id' => Medico::first()->id
        ])->toArray();

        foreach ($consultas as $consulta) {
            $consulta['paciente_id'] = Paciente::inRandomOrder()->first()->id;
            Consulta::create($consulta);
        }
    }
}
