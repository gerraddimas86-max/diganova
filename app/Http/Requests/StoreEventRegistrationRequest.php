<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'ticket_category_id' => ['required', 'integer', 'exists:ticket_categories,id'],
            'payment_proof' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ];
    }

    public function messages(): array
    {
        return [
            'ticket_category_id.required' => 'Pilih kategori tiket terlebih dahulu.',
            'ticket_category_id.exists' => 'Kategori tiket tidak valid.',
            'payment_proof.image' => 'Bukti pembayaran harus berupa gambar.',
            'payment_proof.max' => 'Bukti pembayaran maksimal 3MB.',
        ];
    }
}