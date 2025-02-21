<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Pending'
            ],
            [
                'name' => 'To Pay'
            ],
            [
                'name' => 'To Review Payment'
            ],
            [
                'name' => 'On Process'
            ],
            [
                'name' => 'To Deliver'
            ],
            [
                'name' => 'Cancelled'
            ],
            [
                'name' => 'Rejected'
            ],
            [
                'name' => 'Completed'
            ],
        ];

        foreach ($data as $item) {
            $status = OrderStatus::where('name', $item['name'])->first();
            if (empty($status)) {
                OrderStatus::create($item);
            }
        }
    }
}
