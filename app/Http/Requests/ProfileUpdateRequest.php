<?php

namespace App\Http\Requests;

use App\Http\Requests\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
    return [
        'bio'             => 'sometimes|string|max:1500',
        'hourly_rate'     => 'sometimes|numeric|min:0|max:999.99',
        'phone_number'    => 'sometimes|string|max:20',

        'portfolio_links' => 'sometimes|array',
        'portfolio_links.*' => 'url',

        'skills'          => 'sometimes|array',
        'skills.*'        => 'integer|exists:skills,id',
    ];
}

    
}
