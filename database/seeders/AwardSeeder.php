<?php

namespace Database\Seeders;

use App\Enums\CampusType;
use App\Models\Award;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;


class AwardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $awardNames = [
            'ด้านกิจกรรมเสริมหลักสูตร',
            'ด้านความคิดสร้างสรรค์และนวัตกรรม',
            'ด้านความประพฤติดี'
        ];
        foreach (CampusType::cases() as $campus) {
            Award::factory()
                ->count(count($awardNames))
                ->state(new Sequence(
                    ...array_map(fn($name) => [
                        'name' => $name,
                        'campus' => $campus->value, // หรือชื่อ field ที่คุณใช้เก็บวิทยาเขต
                    ], $awardNames)
                ))
                ->create();
        }
    }
}
