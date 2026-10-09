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
                'password' => Hash::make('admin12345'), // Silakan ganti password ini setelah instalasi
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
                'role'     => 'admin',
            ]
        );

        // 3. Account Staff Operasional & Field Coordinator
        User::firstOrCreate(
            ['email' => 'operasional@alamtour.co.id'],
            [
                'uuid'     => (string) Str::uuid(),
                'nama'     => 'Siti Rahmawati (Staff Operasional)',
                'password' => Hash::make('staff12345'),
                'role'     => 'staff',
            ]
        );

        // 4. Account Staff Keuangan
        User::firstOrCreate(
            ['email' => 'finance@alamtour.co.id'],
            [
                'uuid'     => (string) Str::uuid(),
                'nama'     => 'Dedi Pratama (Staff Finance)',
                'password' => Hash::make('finance123'),
                'role'     => 'staff',
            ]
        );
    }
}
