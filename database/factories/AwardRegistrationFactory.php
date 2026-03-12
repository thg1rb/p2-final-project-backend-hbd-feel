<?php

namespace Database\Factories;

use App\Models\InnovationAwardRegistration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AwardRegistration>
 */
class AwardRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker()->firstName(),
            'last_name'  => $this->faker()->lastName(),

            // ปีการศึกษา 1 - 8
            'academic_year' => $this->faker()->numberBetween(1, 8),

            // สถานะ (ภาษาอังกฤษ)
            'status' => $this->faker()->randomElement([
                'เสร็จสิ้นกระบวนการ',
                'ส่งคำขอแล้ว',
                'ไม่ผ่านการพิจารณา',
            ]),
//            'award_type' => $this->faker->randomElement([
//                'ActivityAwardRegistration',
//                'InnovationAwardRegistration',
//                'BehaviourAwardRegistration',
//            ]),
//            'awardable_id' => $innovation->id,
//            'awardable_type' => InnovationAwardRegistration::class,
        ];
    }
}
