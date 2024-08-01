<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kepala_gudang = User::create([
            'name'          => 'kepala gudang',
            'email'     => 'kepala@gmail.com',
            'password'  => bcrypt('password'),
        ]);
        $kepala_gudang->assignRole('kepala gudang');


        $driver = User::create([
            'name'          => 'Driver',
            'email'     => 'driver@gmail.com',
            'password'  => bcrypt('password'),
        ]);
        $driver->assignRole('driver');

        $manager  = User::create([
            'name'          => 'Manager',
            'email'     => 'manager@gmail.com',
            'password'  => bcrypt('password'),
        ]);
        $manager->assignRole('manager');
    }
}
