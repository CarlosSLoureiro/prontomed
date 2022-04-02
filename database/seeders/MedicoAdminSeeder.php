<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Medico;

class MedicoAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Medico::factory()->make([
            'email' => 'admin@s2medicos.org'
        ])->toArray();

        $admin['senha'] = 'admin000';

        Medico::create($admin);
    }
}
