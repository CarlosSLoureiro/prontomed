<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Consulta;
use App\Models\Observacoes;

class ObservacoesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $observacoes = Observacoes::factory(40)->make()->toArray();
        foreach ($observacoes as $observacao) {
            $observacao['consulta_id'] = Consulta::inRandomOrder()->first()->id;
            Observacoes::create($observacao);
        }
    }
}
