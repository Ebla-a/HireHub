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
            'bio'             => 'nullable|string|max:1500',
            'hourly_rate'     => 'nullable|numeric|min:0|max:999.99',
            'phone_number'    => 'nullable|string|max:20',
            'portfolio_links' => 'nullable|array',
            'portfolio_links.*' => 'url', 
            'skills'          => 'nullable|array',
            'skills.*'        => 'integer|exists:skills,id',
            // 'skills.*.years_of_experience' => 'required_with:skills|integer|min:0|max:50',
        ];
    }
    
}
