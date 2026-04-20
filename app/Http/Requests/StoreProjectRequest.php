<?php

namespace App\Http\Requests;

use App\Rules\ProfanityFilter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
    return auth('sanctum')->check() && auth('sanctum')->user()->role === 'client';
    }
    /**
     * Cleaning the title and description befor validation
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'title' => strip_tags(trim($this->title)),
            'description' => strip_tags(trim($this->description)),
        ]);
    }

    public function rules(): array
    {
       return [
            'title'       => ['required', 'string', 'min:10', 'max:255' ,new ProfanityFilter()], 
            'description' => ['required', 'string', 'min:50' , new ProfanityFilter()],

            'attachments'   => ['nullable', 'array', 'max:5'], 
             'attachments.*' => ['file', 'mimes:pdf,docx,jpg,png,zip', 'max:10240'],
            
            'budget_type' => ['required', Rule::in(['fixed', 'hourly'])],
            'budget'      => [
                'required', 
                'numeric', 
                'min:5',
              
                $this->budget_type === 'hourly' ? 'max:500' : 'max:1000000'
            ],

            'deadline'    => ['required', 'date', 'after:tomorrow'], 
            'city_id'     => ['required', 'exists:cities,id'],
            
            'tags'        => ['nullable', 'array'],
            'tags.*'      => ['exists:tags,id']
        ];
    }

    public function messages(): array
    {
        return [
            'title.min'       => 'The title is very short, please clarify the project idea',
            'deadline.after'  => 'The delivery date must be in the future',
       
            'budget.max'      => $this->budget_type === 'hourly' 
                                 ? 'The hourly rate is very high, please check the amount' 
                                 : 'The budget exceeds the allowed limits',
        ];
    }
}