<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        foreach ($data as $facultyName => $departments) {
            $faculty = \App\Models\Faculty::create([
                'name' => $facultyName
            ]);

            foreach ($departments as $deptName) {
                \App\Models\Department::create([
                    'name' => 'ภาควิชา' . $deptName,
                    'faculty_id' => $faculty->id
                ]);
            }
        }
    }
}
