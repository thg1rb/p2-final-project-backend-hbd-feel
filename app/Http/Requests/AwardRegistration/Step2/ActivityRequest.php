<?php

namespace App\Http\Requests\AwardRegistration\Step2;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActivityRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'activity_types' => ['required', 'array', 'min:1'],
            'activity_types.*' => [
                'required',
                Rule::in(['community', 'competition', 'leadership']),
            ],

            'award_date' => ['required', 'date'],

            'project_name' => ['required', 'string', 'max:255'],
            'team_name' => ['required', 'string', 'max:255'],
            'work_name' => ['required', 'string', 'max:255'],
            'award_name' => ['required', 'string', 'max:255'],
            'organizer' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'activity_types.required' => 'กรุณาเลือกด้านกิจกรรมนอกหลักสูตรอย่างน้อย 1 รายการ',
            'activity_types.array' => 'รูปแบบด้านกิจกรรมไม่ถูกต้อง',
            'activity_types.min' => 'กรุณาเลือกด้านกิจกรรมนอกหลักสูตรอย่างน้อย 1 รายการ',
            'activity_types.*.in' => 'เลือกประเภทกิจกรรมไม่ถูกต้อง',

            'award_date.required' => 'กรุณาระบุวันที่ได้รับรางวัล',
            'award_date.date' => 'รูปแบบวันที่ไม่ถูกต้อง',

            'project_name.required' => 'กรุณากรอกชื่อโครงการหรือรายการที่เข้าร่วม',
            'team_name.required' => 'กรุณากรอกชื่อทีม',
            'work_name.required' => 'กรุณากรอกชื่อผลงาน',
            'award_name.required' => 'กรุณากรอกรางวัลที่ได้รับ',
            'organizer.required' => 'กรุณากรอกหน่วยงานผู้จัด',
        ];
    }
}
