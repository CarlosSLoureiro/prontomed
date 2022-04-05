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
            'email' => 'admin@prontomed.com'
        ])->toArray();

        $admin['senha'] = 'med-admin000';

        Medico::create($admin);
    }
}
