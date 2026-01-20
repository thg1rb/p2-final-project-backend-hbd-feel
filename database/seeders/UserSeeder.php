<?php

namespace Database\Seeders;

use App\Enums\UserRole;
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
        foreach (UserRole::cases() as $role) {
            User::factory()->create([
                'firstName' => $role->value,       // ชื่อตาม Role (เช่น ADMIN)
                'lastName' => 'Account',
                'username' => fake()->userName(),
                'email' => fake()->userName() . '@example.com',
                'password' => 'password',
                'role' => $role,
            ]);
        }

        User::factory()->count(10)->create([
            'role' => UserRole::NISIT,
            'password' => 'password',
        ]);
    }
}
