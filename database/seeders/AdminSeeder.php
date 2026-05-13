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

        // Demo donor
        $donorRole = Role::where('name', 'donor')->first();
        User::firstOrCreate(
            ['email' => 'donor@kolektor.ac.id'],
            [
                'name'     => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role_id'  => $donorRole?->id,
                'phone'    => '08111111111',
                'address'  => 'Kos Mawar No. 12',
                'points'   => 30,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Demo recipient
        $recipientRole = Role::where('name', 'recipient')->first();
        User::firstOrCreate(
            ['email' => 'penerima@kolektor.ac.id'],
            [
                'name'     => 'Siti Rahayu',
                'password' => Hash::make('password'),
                'role_id'  => $recipientRole?->id,
                'phone'    => '08122222222',
                'address'  => 'Asrama Putri Blok B',
                'points'   => 0,
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
