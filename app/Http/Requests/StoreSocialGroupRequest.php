<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreSocialGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $groupId = $this->route('group')?->id;

        return [
            'name' => ['required', 'string', 'min:3', 'max:80'],
            'slug' => [
                'nullable', 'string', 'min:3', 'max:80', 'regex:/^[a-z0-9-]+$/',
                'unique:social_groups,slug' . ($groupId ? ",$groupId" : ''),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'visibility' => ['required', 'in:public,private'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama grup wajib diisi.',
            'name.min' => 'Nama grup minimal 3 karakter.',
            'name.max' => 'Nama grup maksimal 80 karakter.',
            'slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan strip (-).',
            'slug.unique' => 'Slug sudah dipakai grup lain.',
            'visibility.required' => 'Pilih visibilitas grup.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('slug')) {
            $this->merge(['slug' => Str::slug($this->slug)]);
        } elseif ($this->filled('name')) {
            $this->merge(['slug' => Str::slug($this->name)]);
        }
    }
}