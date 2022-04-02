<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MedicoAdminSeeder::class);
        $this->call(PacientesSeeder::class);
        $this->call(ConsultasSeeder::class);
        $this->call(ObservacoesSeeder::class);
    }
}
