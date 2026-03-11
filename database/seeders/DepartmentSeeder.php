<?php

namespace Database\Seeders;

use App\Enums\CampusType;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'คณะวิทยาศาสตร์' => [
                'วิทยาการคอมพิวเตอร์',
                'เคมี',
                'ฟิสิกส์',
                'ชีววิทยา',
                'คณิตศาสตร์'
            ],
            'คณะวิศวกรรมศาสตร์' => [
                'วิศวกรรมคอมพิวเตอร์',
                'วิศวกรรมไฟฟ้า',
                'วิศวกรรมโยธา',
                'วิศวกรรมเครื่องกล',
                'วิศวกรรมเคมี'
            ],
            'คณะบริหารธุรกิจ' => [
                'การบัญชี',
                'การตลาด',
                'การเงิน',
                'การจัดการ',
                'ระบบสารสนเทศเพื่อการจัดการ'
            ],
            'คณะมนุษยศาสตร์' => [
                'ภาษาอังกฤษ',
                'ภาษาไทย',
                'จิตวิทยา',
                'บรรณารักษศาสตร์',
                'ประวัติศาสตร์'
            ],
            'คณะเกษตรศาสตร์' => [
                'กีฏวิทยา',
                'โรคพืช',
                'พืชสวน',
                'พืชไร่',
                'สัตวบาล'
            ],
        ];

        foreach (CampusType::cases() as $campus) {

            // วนลูปสร้างคณะในแต่ละวิทยาเขต
            foreach ($data as $facultyName => $departments) {
                $faculty = Faculty::create([
                    'name' => $facultyName,
                    'campus' => $campus->value,
                ]);

                foreach ($departments as $deptName) {
                    Department::create([
                        'name' => 'ภาควิชา' . $deptName,
                        'faculty_id' => $faculty->id
                    ]);
                }
            }
        }
    }
}
