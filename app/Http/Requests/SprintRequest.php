<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SprintRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sprint_code' => ['required'],
            'project_id' => ['required', 'exists:projects'],
            'name' => ['required'],
            'date_start' => ['required', 'date'],
            'date_end' => ['required', 'date'],
            'status' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
