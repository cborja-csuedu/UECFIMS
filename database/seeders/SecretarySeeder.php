<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SecretarySeeder extends Seeder
{
    /**
     * Run the database seeds for secretary users.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'secretary1@example.com'],
            [
                'name' => 'Secretary One',
                'password' => Hash::make('password'),
                'role' => 'secretary',
                'permissions' => ['verify_member' => true],
            ]
        );

        User::updateOrCreate(
            ['email' => 'secretary2@example.com'],
            [
                'name' => 'Secretary Two',
                'password' => Hash::make('password'),
                'role' => 'secretary',
            ]
        );
    }
}
