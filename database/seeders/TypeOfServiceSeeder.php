<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TypeOfService;

class TypeOfServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    TypeOfService::create([
        'service_name' => 'Cuci & Gosok',
        'price' => 5000,
        'description' => 'Cuci dan gosok per kg'
    ]);

    TypeOfService::create([
        'service_name' => 'Hanya Cuci',
        'price' => 4500,
        'description' => 'Cuci saja per kg'
    ]);

    TypeOfService::create([
        'service_name' => 'Hanya Gosok',
        'price' => 5000,
        'description' => 'Gosok saja per kg'
    ]);

    TypeOfService::create([
        'service_name' => 'Laundry Besar',
        'price' => 7000,
        'description' => 'Selimut, karpet, sprei, mantel'
    ]);
}
}
