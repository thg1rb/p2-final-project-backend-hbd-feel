<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Enums\CampusType;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected static $index = 0;
    protected static $cachedCombinations = null;

    public function definition(): array
    {
        // 1. เตรียมข้อมูล 50 ชุด (10 เทอม x 5 วิทยาเขต) ไว้ใน Memory ครั้งแรกครั้งเดียว
        if (static::$cachedCombinations === null) {
            $baseCombinations = [
                ['academic_year' => 2563, 'semester' => 1],
                ['academic_year' => 2563, 'semester' => 2],
                ['academic_year' => 2564, 'semester' => 1],
                ['academic_year' => 2564, 'semester' => 2],
                ['academic_year' => 2565, 'semester' => 1],
                ['academic_year' => 2565, 'semester' => 2],
                ['academic_year' => 2566, 'semester' => 1],
                ['academic_year' => 2566, 'semester' => 2],
                ['academic_year' => 2567, 'semester' => 1],
                ['academic_year' => 2567, 'semester' => 2],
            ];

            $campuses = CampusType::cases();
            $all = [];

            foreach ($baseCombinations as $combo) {
                foreach ($campuses as $campus) {
                    $all[] = array_merge($combo, ['campus' => $campus]);
                }
            }
            static::$cachedCombinations = $all;
        }

        // 2. ดึงข้อมูลตัวที่ $index ออกมาใช้งาน
        $total = count(static::$cachedCombinations);
        $item = static::$cachedCombinations[self::$index % $total];

        // 3. ขยับ index ไปตัวถัดไปสำหรับการสร้าง Record หน้า
        self::$index++;

        // เช็คเงื่อนไข Status
        $status = ($item['academic_year'] === 2567 && $item['semester'] === 2)
            ? Status::OPENED
            : Status::CLOSED;

        $startDate = fake()->dateTimeBetween('-1 year', 'now');
        $endDate = fake()->dateTimeBetween($startDate, '+6 months');

        return [
            'academic_year' => $item['academic_year'],
            'semester'      => $item['semester'],
            'campus'        => $item['campus'], // จะได้ CampusType Enum
            'status'        => $status,
            'path'          => $status === Status::CLOSED ? "form_1.pdf" : null,
            'start_date'    => $startDate,
            'end_date'      => $endDate,
        ];
    }
}
