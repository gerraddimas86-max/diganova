<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'notes' => ['required', 'string', 'min:5', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'notes.required' => 'Alasan penolakan wajib diisi.',
            'notes.min' => 'Alasan penolakan minimal 5 karakter.',
            'notes.max' => 'Alasan penolakan maksimal 500 karakter.',
        ];
    }
}