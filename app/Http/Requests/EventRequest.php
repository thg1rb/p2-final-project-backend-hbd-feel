<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
        $event = $this->route('event');
        $eventId = $event instanceof \App\Models\Event ? $event->id : $event;

        return [
            'semester' => 'required|integer|in:1,2',
            'campus' => 'required|string|in:' . implode(',', array_map(fn($case) => $case->value, \App\Enums\CampusType::cases())),
            'start_date' => 'required|date|after_or_equal::today',
            'end_date' => 'required|date|after:start_date',
            'status' => [
                'required',
                'string',
                'in:' . implode(',', array_map(fn($case) => $case->value, \App\Enums\Status::cases())),
                function ($attribute, $value, $fail) use ($eventId) {
                    // Check if trying to set status to OPENED
                    if ($value === \App\Enums\Status::OPENED->value) {
                        // Check if there's already an OPENED event in the same campus (exclude current event when editing)
                        $hasOpenedEvent = \App\Models\Event::where('status', \App\Enums\Status::OPENED->value)
                            ->where('campus', auth()->user()->campus)
                            ->when($eventId, function ($query) use ($eventId) {
                                return $query->where('id', '!=', $eventId);
                            })
                            ->exists();

                        if ($hasOpenedEvent) {
                            $fail('ไม่สามารถเปิดรอบการให้รางวัลได้ เนื่องจากมีรอบที่เปิดอยู่แล้ว');
                        }
                    }
                },
            ],
            'academic_year' => [
                'required',
                'integer',
                'min:2500',
                'max:2700',
                function ($attribute, $value, $fail) use ($event, $eventId) {
                    $semester = $this->input('semester');

                    // Skip validation if updating and academic_year + semester are unchanged
                    if ($event instanceof \App\Models\Event) {
                        if ($event->academic_year == $value && $event->semester == $semester) {
                            return;
                        }
                    }

                    $exists = \App\Models\Event::where('academic_year', $value)
                        ->where('semester', $semester)
                        ->where('campus', auth()->user()->campus)
                        ->when($eventId, function ($query) use ($eventId) {
                            return $query->where('id', '!=', $eventId);
                        })
                        ->exists();

                    if ($exists) {
                        $fail('ปีการศึกษาและภาคเรียนนี้มีอยู่ในระบบแล้ว');
                    }
                },
            ],
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
