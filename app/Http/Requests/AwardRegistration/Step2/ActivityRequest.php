<?php

namespace App\Http\Requests\AwardRegistration\Step2;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActivityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'achievement'     => ['required', 'string'],
            'activity_hours'  => ['nullable', 'integer', 'min:1', 'max:200'],
            'role'            => ['nullable', 'string', 'max:255'],
            'additional_info' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'achievement.required' => 'กรุณาระบุ achievement',
            'achievement.string' => 'achievement ต้องเป็นตัวอักษร',
            'activity_hours.integer' => 'ชั่วโมงกิจกรรมต้องเป็นตัวเลข',
            'activity_hours.min' => 'ต้องมีชั่วโมงกิจกรรมตั้งแต่ 1 ชั่วโมงขึ้นไป',
            'activity_hours.max' => 'ต้องมีชั่วโมงกิจกรรมไม่เกิน 200 ชั่วโมง',
            'role.string' => 'role ต้องเป็นตัวอักษร',
            'role.max' => 'role ต้องมีจำนวนตัวอักษรไม่เกิน 255 ตัว',
            'additional_info.string' => 'additional_info ต้องเป็นตัวอักษร',
        ];
    }
}
