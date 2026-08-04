<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVisitRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $visitId = $this->route('visit') ? $this->route('visit')->id : null;

        return [
            'visit_code' => 'required|string|max:30|unique:visits,visit_code,'.$visitId,
            'guest_id' => 'required|exists:guests,id',
            'branch_id' => 'required|exists:branches,id',
            'purpose_id' => 'required|exists:visit_purposes,id',
            'source_id' => 'nullable|exists:visit_sources,id',
            'assigned_to' => 'nullable|exists:users,id',
            'scheduled_at' => 'nullable|date',
            'check_in_at' => 'required|date',
            'meeting_start_at' => 'nullable|date',
            'check_out_at' => 'nullable|date',
            'status' => 'required|string|max:30',
            'queue_number' => 'nullable|integer',
            'meeting_result' => 'nullable|string',
            'potential_level' => 'nullable|string|max:20',
            'next_action' => 'nullable|string',
            'follow_up_at' => 'nullable|date',
            'is_converted_to_lead' => 'boolean',
        ];
    }
}
