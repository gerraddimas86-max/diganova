<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:1', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'Post tidak boleh kosong.',
            'body.max' => 'Post maksimal 500 karakter.',
        ];
    }
}