<?php


namespace App\Http\Requests;

use App\Rules\ProfanityFilter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('sanctum')->check()
            && auth('sanctum')->user()->role === 'client';
    }

    protected function prepareForValidation()
{
    if ($this->has('title')) {
        $this->merge([
            'title' => strip_tags(trim($this->title)),
        ]);
    }

    if ($this->has('description')) {
        $this->merge([
            'description' => strip_tags(trim($this->description)),
        ]);
    }
}


    public function rules(): array
    {
        return [
            'title'       => ['sometimes', 'string', 'min:10', 'max:255', new ProfanityFilter()],
            'description' => ['sometimes','string', 'min:50', new ProfanityFilter()],

            'attachments'   => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:pdf,docx,jpg,png,zip', 'max:10240'],

            'budget_type' => ['sometimes', Rule::in(['fixed', 'hourly'])],
            'budget'      => [
                'sometimes',
                'numeric',
                'min:5',
                $this->budget_type === 'hourly' ? 'max:500' : 'max:1000000'
            ],

            'deadline'    => ['sometimes', 'date', 'after:tomorrow'],
            'city_id'     => ['sometimes', 'exists:cities,id'],

            'tags'        => ['nullable', 'array'],
            'tags.*'      => ['exists:tags,id']
        ];
    }

    public function messages(): array
    {
        return [
            'title.min'      => 'The title is very short, please clarify the project idea',
            'deadline.after' => 'The delivery date must be in the future',
            'budget.max'     => $this->budget_type === 'hourly'
                                ? 'The hourly rate is very high, please check the amount'
                                : 'The budget exceeds the allowed limits',
        ];
    }
}

