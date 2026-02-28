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
        $this->call([
            DepartmentSeeder::class,
            EventSeeder::class,
            UserSeeder::class,
        ]);

        User::factory(50)->create();

        $this->call([
            AwardSeeder::class,
            UserAwardSeeder::class,
            EventAwardSeeder::class,
            EventUserSeeder::class,
            AwardRegistrationSeeder::class,
            ApplicationSeeder::class,
            ApprovalSeeder::class,
        ]);
    }
}
