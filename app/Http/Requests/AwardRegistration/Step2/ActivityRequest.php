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
            'activity_hours'  => ['required', 'integer', 'min:1', 'max:200'],
        ];
    }

    public function messages(): array
    {
        return [
            'activity_hours.required' => 'กรุณากรอกชั่วโมงกิจกรรม',
            'activity_hours.integer' => 'ชั่วโมงกิจกรรมต้องเป็นตัวเลข',
            'activity_hours.min' => 'ต้องมีชั่วโมงกิจกรรมตั้งแต่ 1 ชั่วโมงขึ้นไป',
            'activity_hours.max' => 'ต้องมีชั่วโมงกิจกรรมไม่เกิน 200 ชั่วโมง',
        ];
    }
}
