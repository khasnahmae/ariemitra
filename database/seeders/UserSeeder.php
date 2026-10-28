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
        User::firstOrCreate(
            ['email' => 'owner@alamtour.co.id'],
            [
                'uuid'     => (string) Str::uuid(),
                'nama'     => 'Owner',
                'password' => Hash::make('owner12345'),
                'role'     => 'owner',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@alamtour.co.id'],
            [
                'uuid'     => (string) Str::uuid(),
                'nama'     => 'Admin',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );
        User::firstOrCreate(
            ['email' => 'khasnahm@gmail.com'],
            [
                'uuid'     => (string) Str::uuid(),
                'nama'     => 'Khasnah',
                'password' => Hash::make('khasnah'),
                'role'     => 'client',
            ]
        );
    }
}
