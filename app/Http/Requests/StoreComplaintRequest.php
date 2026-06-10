<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'complaint_category_id' => [
                'required',
                'exists:complaint_categories,id',
            ],

            'is_anonymous' => [
                'required',
                'boolean',
            ],

            'name' => [
                'required_if:is_anonymous,false',
                'nullable',
                'string',
                'max:255',
            ],

            'phone' => [
                'required_if:is_anonymous,false',
                'nullable',
                'string',
                'max:15',
            ],

            'nik' => [
                'required_if:is_anonymous,false',
                'nullable',
                'digits:16',
            ],

            'description' => [
                'required',
                'string',
                'min:10',
            ],

            'attachments' => [
                'nullable',
                'array',
                'max:5',
            ],

            'attachments.*' => [
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120', // 5MB
            ],

            'room_id' => [
                'required',
                'exists:rooms,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required_if' =>
                'Nama wajib diisi jika tidak anonim.',

            'phone.required_if' =>
                'Nomor HP wajib diisi jika tidak anonim.',

            'nik.required_if' =>
                'NIK wajib diisi jika tidak anonim.',

            'nik.digits' =>
                'NIK harus terdiri dari 16 digit.',

            'attachments.max' =>
                'Maksimal 5 lampiran.',

            'attachments.*.mimes' =>
                'Lampiran hanya boleh JPG, PNG, atau PDF.',

            'attachments.*.max' =>
                'Ukuran file maksimal 5 MB.',

            'complaint_category_id.required' =>
                'Kategori keluhan wajib dipilih.',

            'description.required' =>
                'Deskripsi keluhan/aduan wajib diisi.',
            
            'room_id.required' =>
                'Ruangan perawatan wajib dipilih'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_anonymous' => filter_var(
                $this->is_anonymous,
                FILTER_VALIDATE_BOOLEAN
            ),
        ]);
    }
}