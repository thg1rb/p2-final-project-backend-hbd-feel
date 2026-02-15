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
        \App\Models\Award::factory()
            ->count(3)
            ->state(new \Illuminate\Database\Eloquent\Factories\Sequence(
                ['name' => 'ด้านกิจกรรมเสริมหลักสูตร'],
                ['name' => 'ด้านความคิดสร้างสรรค์และนวัตกรรม'],
                ['name' => 'ด้านความประพฤติดี']
            ))
            ->create();
    }
}
