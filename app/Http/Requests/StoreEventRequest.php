<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'in:music,cosplay,art,game_tournament,festival,other'],
            'location' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'start_date' => ['required', 'date', 'after:now'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'registration_deadline' => ['nullable', 'date', 'before:start_date'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,published,ongoing,completed,cancelled'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul event wajib diisi.',
            'description.required' => 'Deskripsi wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'location.required' => 'Lokasi wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.after' => 'Tanggal mulai harus di masa depan.',
            'end_date.after' => 'Tanggal selesai harus setelah tanggal mulai.',
            'banner_image.image' => 'Banner harus berupa gambar.',
            'banner_image.max' => 'Banner maksimal 2MB.',
        ];
    }
}