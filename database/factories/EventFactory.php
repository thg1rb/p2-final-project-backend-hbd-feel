<?php

namespace Database\Factories;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected static $index = 0;
    public function definition(): array
    {
        $combinations = [
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

        // ดึงค่าตามลำดับ index ปัจจุบัน
        // ใช้ % เพื่อป้องกัน Error กรณีสั่งสร้างเกินจำนวนที่มีใน array (จะวนกลับมาเริ่มใหม่)
        $combination = $combinations[self::$index % count($combinations)];

        // เพิ่มค่า index สำหรับการเรียกครั้งต่อไป
        self::$index++;

        $status = ($combination['academic_year'] === 2567 && $combination['semester'] === 2)
            ? Status::OPENED
            : Status::CLOSED;

        $startDate = $this->faker->dateTimeBetween('+1 week', '+3 months');
        $endDate = $this->faker->dateTimeBetween($startDate, '+6 months');

        return [
            'academic_year' => $combination['academic_year'],
            'semester' => $combination['semester'],
            'status' => $status,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}
