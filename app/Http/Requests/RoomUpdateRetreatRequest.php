<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomUpdateRetreatRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'retreat_id' => 'integer|min:0',
            'registrations' => 'array',
            'registrations.*' => 'nullable|integer|min:0',
            'notes' => 'array',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [];
    }
}
