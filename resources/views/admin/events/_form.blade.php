<form method="POST" action="{{ $action }}" enctype="multipart/form-data"
      class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    {{-- Title --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Event *</label>
        <input type="text" name="title" value="{{ old('title', $event?->title) }}" required
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">
        @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Description --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label>
        <textarea name="description" rows="5" required
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">{{ old('description', $event?->description) }}</textarea>
        @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Category & Status --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
            <select name="category" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">
                <option value="">Pilih kategori</option>
                @foreach (['music' => 'Musik', 'cosplay' => 'Cosplay', 'art' => 'Seni', 'game_tournament' => 'Turnamen Game', 'festival' => 'Festival', 'other' => 'Lainnya'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('category', $event?->category) === $val)>{{ $label }}</option>
                @endforeach
            </select>
            @error('category') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
            <select name="status" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">
                @foreach (['draft' => 'Draft', 'published' => 'Published', 'ongoing' => 'Ongoing', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('status', $event?->status ?? 'draft') === $val)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Location & Address --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi *</label>
            <input type="text" name="location" value="{{ old('location', $event?->location) }}" required
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none"
                   placeholder="Jakarta Convention Center">
            @error('location') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
            <input type="text" name="address" value="{{ old('address', $event?->address) }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none"
                   placeholder="Jl. Gatot Subroto No.1, Jakarta">
            @error('address') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Coordinates --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
            <input type="number" step="0.0000001" name="latitude" value="{{ old('latitude', $event?->latitude) }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none"
                   placeholder="-6.2146">
            @error('latitude') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
            <input type="number" step="0.0000001" name="longitude" value="{{ old('longitude', $event?->longitude) }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none"
                   placeholder="106.8009">
            @error('longitude') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Dates --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai *</label>
            <input type="datetime-local" name="start_date"
                   value="{{ old('start_date', $event?->start_date?->format('Y-m-d\TH:i')) }}" required
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">
            @error('start_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai *</label>
            <input type="datetime-local" name="end_date"
                   value="{{ old('end_date', $event?->end_date?->format('Y-m-d\TH:i')) }}" required
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">
            @error('end_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deadline Registrasi</label>
            <input type="datetime-local" name="registration_deadline"
                   value="{{ old('registration_deadline', $event?->registration_deadline?->format('Y-m-d\TH:i')) }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">
            @error('registration_deadline') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Banner & Max --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Banner Event</label>
            @if ($event?->banner_image)
                <div class="mb-2">
                    <img src="{{ Storage::url($event->banner_image) }}" class="h-24 rounded-lg border border-gray-200" alt="Banner">
                    <p class="text-xs text-gray-500 mt-1">Banner saat ini. Upload baru untuk mengganti.</p>
                </div>
            @endif
            <input type="file" name="banner_image" accept="image/*"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none bg-white">
            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, WebP. Maks 2MB.</p>
            @error('banner_image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Max Peserta</label>
            <input type="number" name="max_participants" value="{{ old('max_participants', $event?->max_participants) }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none"
                   placeholder="5000">
            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada batas.</p>
            @error('max_participants') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3 pt-4 border-t border-gray-100">
        <button type="submit"
                class="bg-nova-600 hover:bg-nova-700 text-white px-6 py-2.5 rounded-lg font-medium transition">
            {{ $submitLabel }}
        </button>
        <a href="{{ route('admin.events.index') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium transition">
            Batal
        </a>
    </div>
</form>