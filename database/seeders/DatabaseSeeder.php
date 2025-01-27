<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(BasicDataSeeder::class);
        $this->call(StateSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(ZillaSeeder::class);
        $this->call(AssemblyConstituencySeeder::class);
        $this->call(PincodeSeeder::class);
        $this->call(MandalSeeder::class);
        $this->call(BoothSeeder::class);
    }
}
