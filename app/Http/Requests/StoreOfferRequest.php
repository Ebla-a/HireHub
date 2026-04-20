<?php

namespace App\Http\Requests;

use App\Models\Project;
use App\Rules\ProfanityFilter;
use Illuminate\Foundation\Http\FormRequest;

class StoreOfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check() && auth('sanctum')->user()->role === 'freelancer';
    }
    /**
     * cleaning the data before check
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'proposal_letter' => strip_tags(trim($this->proposal_letter)),
        ]);
    }

    /**
     * Get the validation rules that apply to the request
     * @return array{amount: string, delivery_days: string, owner_check: (callable(mixed ,mixed ,mixed ):void)[], project_id: array<(callable(mixed ,mixed ,mixed ):void)|string>, proposal_letter: array<ProfanityFilter|string>}
     */
    public function rules(): array
    {
      
        return [
            'project_id' => [
                'required',
                'exists:projects,id',
                function ($attribute, $value, $fail) {
                    $project = Project::find($value);
                    if ($project && $project->status !== 'open') {
                        $fail('Sorry ,this project is no longer accepting proposals');
                    }
                    if ($project->user_id === auth('sanctum')->id()) {
                    $fail('You cannot submit a bid on your own project.');

                }

                },
            ],

            'amount' => 'required|numeric|min:5',
            'delivery_days' => 'required|integer|min:1',
      
            'proposal_letter' => [
                'required', 
                'string', 
                'min:30', 
                new ProfanityFilter()
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'proposal_letter.min' => 'The cover letter is very short please write a convincing pitch for the client',
            'amount.min' => 'The minimum amount to apply is $5',
        ];
    }
}
  
