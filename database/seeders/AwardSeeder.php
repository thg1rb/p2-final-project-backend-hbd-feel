<?php

namespace Database\Seeders;

use App\Models\Award;
use Database\Factories\AwardFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class AwardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Award::factory()
            ->count(2)
            ->awardConfigurations()
            ->create();
    }
}
