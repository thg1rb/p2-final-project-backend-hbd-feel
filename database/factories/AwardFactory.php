<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Award>
 */
class AwardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'reward' => $this->faker->randomFloat(2, 1000, 5000),
            'form_schema' => [],
        ];
    }
    public function awardConfigurations()
    {
        return $this->sequence(
            [
                'name' => 'Extracurricular Activities',
                'form_schema' => [
                    [
                        'key' => 'qualification',
                        'label' => 'ด้านกิจกรรมนอกหลักสูตร(มีคุณสมบัติข้อใดก็ได้)',
                        'type' => 'select',
                        'options' => [
                            'เป็นนิสิตที่ดำเนินกิจกรรมและต้องแสดงให้เห็นว่า เมื่อดำเนินกิจกรรมแล้ว ชาวบ้าน ชุมชนในท้องถิ่น หรือผู้เข้าร่วมกิจกรรมได้รับประโยชน์อย่างไรจากการดำเนินกิจกรรมก่อให้เกิดประโยชน์ต่อส่วนรวมและเป็นการสร้างชื่อเสียง เกียรติคุณต่อคณะหรือมหาวิทยาลัย',
                            'เข้าร่วมแข่งขัน ทางวิชาการหรือศิลปวัฒนธรรมระดับอุดมศึกษาระดับชาติหรือระดับนานาชาติและได้รับรางวัลใด รางวัลหนึ่งจากการแข่งขัน',
                            'ดำรงตำแหน่งนายกองค์การบริหารองค์การนิสิต ประธานสภาผู้แทนนิสิต หรือนายกสโมสรนิสิต (กองกิจการนิสิตเสนอชื่อโดยตำแหน่ง)'
                        ],
                        'required' => true
                    ],
                    ['key' => 'award_date', 'label' => 'วันที่ได้รับรางวัล', 'type' => 'date', 'required' => true],
                    ['key' => 'compettition', 'label' => 'โครงการที่เข้าแข่งขัน /รายการแข่งขัน', 'type' => 'text', 'required' => true],
                    ['key' => 'team_name', 'label' => 'ชื่อทีม', 'type' => 'text', 'required' => true],
                    ['key' => 'project_name', 'label' => 'ชื่อผลงานที่ได้รับรางวัล', 'type' => 'text', 'required' => true],
                    ['key' => 'award', 'label' => 'รางวัลที่ได้รับ', 'type' => 'text', 'required' => true],
                    ['key' => 'organization', 'label' => 'หน่วยงานผู้จัด', 'type' => 'text', 'required' => false],
                ]
            ],
            [
                'name' => 'Creativity & Innovation',
                'form_schema' => [
                    [
                        'key' => 'qualification',
                        'label' => 'ด้านความคิดสร้างสรรค์และนวัตกรรม',
                        'type' => 'select',
                        'options' => [
                            'ต้องได้รับรางวัลจากการประกวดหรือการแข่งขันระดับอุดมศึกษาระดับชาติหรือระดับนานาชาติที่มีหน่วยงานภาครัฐหรือเอกชนเป็นผู้จัด',
                        ],
                        'required' => true
                    ],
                    ['key' => 'award_date', 'label' => 'วันที่ได้รับรางวัล', 'type' => 'date', 'required' => true],
                    ['key' => 'compettition', 'label' => 'โครงการที่เข้าแข่งขัน /รายการแข่งขัน', 'type' => 'text', 'required' => true],
                    ['key' => 'team_name', 'label' => 'ชื่อทีม', 'type' => 'text', 'required' => true],
                    ['key' => 'project_name', 'label' => 'ชื่อผลงานที่ได้รับรางวัล', 'type' => 'text', 'required' => true],
                    ['key' => 'award', 'label' => 'รางวัลที่ได้รับ', 'type' => 'text', 'required' => true],
                    ['key' => 'organization', 'label' => 'หน่วยงานผู้จัด', 'type' => 'text', 'required' => false],
                ]
            ]
        );
    }
}
