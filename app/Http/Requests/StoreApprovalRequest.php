<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'application_id' => ['required', 'exists:applications,id'],
            'reason' => ['required', 'string', 'max:1000'],
            'status' => ['required', 'in:APPROVED,REJECTED'],
        ];
    }
}
