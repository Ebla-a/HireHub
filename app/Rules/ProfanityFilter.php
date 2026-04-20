<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ProfanityFilter implements ValidationRule
{
    protected array $badWords = [
        'badword1', 'badword2', 'fraudster', 'fraudster', 'Fraud'
    ];

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $loweredValue = mb_strtolower($value);

        foreach ($this->badWords as $word) {
            if (str_contains($loweredValue, mb_strtolower($word))) {
                $fail("The field contains inappropriate or forbidden words");
                break;
            }
        }
    }
    }

