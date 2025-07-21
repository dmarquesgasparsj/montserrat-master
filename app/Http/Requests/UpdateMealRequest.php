<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'integer|min:1|required',
            'retreat_id' => 'integer|exists:event,id|required',
            'meal_date' => 'date|required',
            'meal_type' => 'string|required',
            'vegetarian_count' => 'integer|nullable',
            'gluten_free_count' => 'integer|nullable',
            'dairy_free_count' => 'integer|nullable',
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
