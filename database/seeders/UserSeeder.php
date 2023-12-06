<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => "On-Me",
            'last_name' => "Admin",
            'full_name' => "On-Me Admin",
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'phone' => '8008008000',
            'company_name' => 'On-Me',
            'role' => 'admin',
            'status' => 'active',
        ]);
    }
}
