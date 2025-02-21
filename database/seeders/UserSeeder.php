<?php
namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'email' => 'admin@paper.com',
                'password' => Hash::make('secret'),
                'active' => 1,
                'email_verified_at' => Carbon::now(),
                'is_admin' => 1,
                'is_staff' => 0,
            ],
            [
                'email' => 'staff@paper.com',
                'password' => Hash::make('secret'),
                'active' => 1,
                'email_verified_at' => Carbon::now(),
                'is_admin' => 1,
                'is_staff' => 1,
            ],
            [
                'email' => 'user@paper.com',
                'password' => Hash::make('secret'),
                'active' => 1,
                'email_verified_at' => Carbon::now(),
                'is_admin' => 0,
                'is_staff' => 0,
            ],
        ];

        foreach ($data as $item) {
            $user = User::where('email', $item['email'])->first();
            if (empty($user)) {
                User::create($item);
            } else {
                // $user->update($item);
            }
        }
    }
}
