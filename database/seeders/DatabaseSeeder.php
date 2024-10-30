<?php

namespace Database\Seeders;

use App\Models\Purpose;
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
        $this->call(MethodSeeder::class);
        $this->call(SubMethodSeeder::class);
        $this->call(WorkSeeder::class);
        $this->call(EducationSeeder::class);
        $this->call(PurposeSeeder::class);
        $this->call(ServiceSeeder::class);
    }
}
