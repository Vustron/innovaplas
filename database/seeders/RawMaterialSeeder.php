<?php

namespace Database\Seeders;

use App\Models\RawMaterial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RawMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Linear Low-Density Polyethylene (LLDPE)',
                'batch_number' => '',
                'quantity' => 0
            ],
            [
                'name' => 'Low Density Polyethylene (LDPE)',
                'batch_number' => '',
                'quantity' => 0
            ],
            [
                'name' => 'High Density Polyethylene (HDPE)',
                'batch_number' => '',
                'quantity' => 0
            ],
            [
                'name' => 'Polyethylene (PP)',
                'batch_number' => '',
                'quantity' => 0
            ],
        ];

        foreach ($data as $item) {
            $status = RawMaterial::where('name', $item['name'])->first();
            if (empty($status)) {
                RawMaterial::create($item);
            }
        }
    }
}
