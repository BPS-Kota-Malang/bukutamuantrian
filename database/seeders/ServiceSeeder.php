<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create(['name' => 'Perpustakan']);   //
        Service::create(['name' => 'Penjualan Produk BPS']);   //
        Service::create(['name' => 'Konsultasi Data Statistik']);   //
        Service::create(['name' => 'Rekomendasi Statistik']);   //

    }
}
