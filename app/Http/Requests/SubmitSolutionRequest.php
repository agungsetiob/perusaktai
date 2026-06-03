<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitSolutionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'solution' => [
                'required',
                'string',
                'min:10',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'solution.required' =>
                'Solusi wajib diisi.',

            'solution.min' =>
                'Solusi minimal 10 karakter.',
        ];
    }
}