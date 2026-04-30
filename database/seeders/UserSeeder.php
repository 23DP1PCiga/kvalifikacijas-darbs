<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(20)->create();

        User::create([
        'user_name' => 'polina',
        'email' => 'polina@gmail.com',
        'password' => bcrypt('110807'),
        'role' => 'admin'
    ]);
    }
}
