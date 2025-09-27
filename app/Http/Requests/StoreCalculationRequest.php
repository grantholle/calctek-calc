<?php

namespace App\Http\Requests;

use App\Services\CalculatorService;
use Illuminate\Foundation\Http\FormRequest;

class StoreCalculationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'expression' => [
                'required',
                'string',
                function (string $attribute, string $value, \Closure $fail) {
                    $calculator = new CalculatorService($value);

                    if ($message = $calculator->validationMessage()) {
                        $fail($message);
                    }
                },
            ],
        ];
    }
}
