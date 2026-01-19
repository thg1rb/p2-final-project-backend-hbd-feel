<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('1234567890'),
        ]);

        User::factory(10)->create();

        $this->call([EventSeeder::class, AwardSeeder::class, UserAwardSeeder::class, EventAwardSeeder::class]);
    }
}
