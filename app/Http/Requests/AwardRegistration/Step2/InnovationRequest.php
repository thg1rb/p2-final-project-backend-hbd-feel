<?php

namespace App\Http\Requests\AwardRegistration\Step2;

use Illuminate\Foundation\Http\FormRequest;

class InnovationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
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
