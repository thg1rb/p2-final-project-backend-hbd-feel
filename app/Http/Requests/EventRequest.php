<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'status' => 'required|string|in:' . implode(',', array_map(fn($case) => $case->value, \App\Enums\Status::cases())),
            'academic_year' => 'required|integer|min:2500|max:2700',
            'semester' => 'required|integer|in:1,2',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
        ];
    }

    /**
     * Get the validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'กรุณาระบุชื่อรอบการให้รางวัล',
            'name.string' => 'ชื่อรอบการให้รางวัลต้องเป็นข้อความ',
            'name.max' => 'ชื่อรอบการให้รางวัลต้องไม่เกิน 255 ตัวอักษร',
            'status.required' => 'กรุณาระบุสถานะ',
            'status.in' => 'สถานะต้องเป็นเปิดรอบการให้รางวัลหรือปิดรอบการให้รางวัลเท่านั้น',
            'academic_year.required' => 'กรุณาระบุปีการศึกษา',
            'academic_year.integer' => 'ปีการศึกษาต้องเป็นตัวเลข',
            'academic_year.min' => 'ปีการศึกษาต้องไม่ต่ำกว่า 2500',
            'academic_year.max' => 'ปีการศึกษาต้องไม่เกิน 2700',
            'semester.required' => 'กรุณาระบุภาคเรียน',
            'semester.in' => 'ภาคเรียนต้องเป็น 1 หรือ 2 เท่านั้น',
            'start_date.required' => 'กรุณาระบุวันที่เริ่มต้น',
            'start_date.date' => 'วันที่เริ่มต้นต้องเป็นรูปแบบวันที่ที่ถูกต้อง',
            'start_date.after' => 'วันที่เริ่มต้นต้องอยู่หลังจากวันนี้',
            'end_date.required' => 'กรุณาระบุวันที่สิ้นสุด',
            'end_date.date' => 'วันที่สิ้นสุดต้องเป็นรูปแบบวันที่ที่ถูกต้อง',
            'end_date.after' => 'วันที่สิ้นสุดต้องอยู่หลังจากวันที่เริ่มต้น',
        ];
    }
}
