<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();

        User::firstOrCreate(
            ['email' => 'admin@kolektor.ac.id'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password'),
                'role_id'  => $adminRole?->id,
                'phone'    => '08100000000',
                'address'  => 'Kampus Universitas',
                'points'   => 0,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Demo user
        $userRole = Role::where('name', 'user')->first();
        User::firstOrCreate(
            ['email' => 'user@kolektor.ac.id'],
            [
                'name'     => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role_id'  => $userRole?->id,
                'phone'    => '08111111111',
                'address'  => 'Kos Mawar No. 12',
                'points'   => 30,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Demo courier
        $courierRole = Role::where('name', 'courier')->first();
        User::firstOrCreate(
            ['email' => 'kurir@kolektor.ac.id'],
            [
                'name'     => 'Andi Prasetyo',
                'password' => Hash::make('password'),
                'role_id'  => $courierRole?->id,
                'phone'    => '08133333333',
                'address'  => 'Perumahan Kampus Blok C',
                'points'   => 0,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
