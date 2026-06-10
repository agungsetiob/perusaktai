<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $room = $this->route('room');

        return [
            'name' => [
                'required',
                'max:255',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }
}