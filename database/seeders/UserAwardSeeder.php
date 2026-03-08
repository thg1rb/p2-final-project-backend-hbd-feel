<?php

namespace Database\Seeders;

use App\Models\Award;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserAwardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $awards = Award::all();

        foreach ($users as $user) {
            if ($user->role === "NISIT_DEV") {
                continue;
            }

            $user->awards()->attach(
                $awards->random(1)->pluck('id')->toArray(),
                // [
                //     'round' => '2569/1',
                // ]
            );
        }
    }
}
