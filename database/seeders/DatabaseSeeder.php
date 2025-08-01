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
        // User::factory(10)->create();

        User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => bcrypt('admin@123'),
        ]);

        User::create([
            'name' => 'akshay1',
            'username' => 'akshay1',
            'password' => bcrypt('admin@123'),
        ]);

        User::create([
            'name' => 'akshay2',
            'username' => 'akshay2',
            'password' => bcrypt('admin@123'),
        ]);

        User::create([
            'name' => 'akshay3',
            'username' => 'akshay3',
            'password' => bcrypt('admin@123'),
        ]);

        User::create([
            'name' => 'akshay4',
            'username' => 'akshay4',
            'password' => bcrypt('admin@123'),
        ]);

        User::create([
            'name' => 'akshay5',
            'username' => 'akshay5',
            'password' => bcrypt('admin@123'),
        ]);

        User::create([
            'name' => 'akshay6',
            'username' => 'akshay6',
            'password' => bcrypt('admin@123'),
        ]);
    }
}
