<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            [
                'email' => 'admin@example.com',
            ],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('Admin@123456'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('admin');
    }
}
