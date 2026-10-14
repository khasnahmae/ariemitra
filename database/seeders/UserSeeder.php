<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // 1. Account Super Admin / Director
        User::firstOrCreate(
            ['email' => 'admin@alamtour.co.id'],
            [
                'uuid'     => (string) Str::uuid(),
                'nama'     => 'Administrator Utama',
                'password' => Hash::make('admin12345'),
                'role'     => 'admin',
            ]
        );

        // 2. Account Manager Marketing (Pembuat Proposal Utama)
        User::firstOrCreate(
            ['email' => 'marketing@alamtour.co.id'],
            [
                'uuid'     => (string) Str::uuid(),
                'nama'     => 'Budi Santoso (Marketing Manager)',
                'password' => Hash::make('marketing123'),
                'role'     => 'staff',
            ]
        );
    }
}
