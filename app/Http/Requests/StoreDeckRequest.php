<?php

namespace App\Http\Requests;

use App\Models\Card;
use Illuminate\Foundation\Http\FormRequest;

class StoreDeckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:50'],
            'slot' => ['nullable', 'integer', 'min:1', 'max:3'],
            'cards' => ['required', 'array', 'size:10'],
            'cards.*' => ['integer', 'exists:cards,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama deck wajib diisi.',
            'name.max' => 'Nama deck maksimal 50 karakter.',
            'cards.required' => 'Deck harus berisi 10 kartu.',
            'cards.size' => 'Deck harus tepat 10 kartu.',
            'cards.*.exists' => 'Kartu yang dipilih tidak valid.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $cards = $this->input('cards', []);
            $unique = array_unique($cards);

            if (count($unique) !== count($cards)) {
                $validator->errors()->add('cards', 'Tidak boleh ada kartu duplikat dalam satu deck.');
            }

            // Pastikan semua kartu aktif
            if (!empty($cards)) {
                $activeCount = Card::whereIn('id', $cards)->where('is_active', true)->count();
                if ($activeCount !== count($cards)) {
                    $validator->errors()->add('cards', 'Ada kartu yang tidak aktif atau tidak tersedia.');
                }
            }
        });
    }
}