<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'note' => [
                'required',
                'string',
                'min:5',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'note.required' =>
                'Alasan penolakan wajib diisi.',
        ];
    }
}