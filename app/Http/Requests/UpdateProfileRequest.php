<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'bio' => ['nullable', 'string', 'max:160'],
            'avatar_id' => ['nullable', 'integer', 'exists:avatars,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'bio.max' => 'Bio maksimal 160 karakter.',
            'avatar_id.exists' => 'Avatar tidak ditemukan.',
        ];
    }
}