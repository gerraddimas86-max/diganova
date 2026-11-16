@php
    /* ====== Icon library ====== */
    $icons = [
        'type'          => '<path d="M4 7V4h16v3M9 20h6M12 4v16"/>',
        'file-text'     => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4M10 9H8M16 13H8M16 17H8"/>',
        'tag'           => '<path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/>',
        'activity'      => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
        'map-pin'       => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'compass'       => '<path d="m16.24 7.76-1.804 5.411a2 2 0 0 1-1.265 1.265L7.76 16.24l1.804-5.411a2 2 0 0 1 1.265-1.265z"/><circle cx="12" cy="12" r="10"/>',
        'calendar'      => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'calendar-clock'=> '<path d="M21 7.5V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3.5"/><path d="M16 2v4M8 2v4M3 10h5"/><path d="M17.5 17.5 16 16.3V14"/><circle cx="16" cy="16" r="6"/>',
        'clock'         => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'image'         => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>',
        'upload-cloud'  => '<path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path d="M12 12v9"/><path d="m16 16-4-4-4 4"/>',
        'users'         => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'save'          => '<path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7M7 3v4a1 1 0 0 0 1 1h7"/>',
        'x'             => '<path d="M18 6 6 18M6 6l12 12"/>',
        'alert'         => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4M12 17h.01"/>',
        'sparkles'      => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4M22 5h-4M4 17v2M5 18H3"/>',
    ];
    $icon = fn (string $name, string $class = 'w-4 h-4') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';
@endphp

<style>
    /* Field label style */
    .form-label {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: rgba(9,14,57,.7);
        margin-bottom: .4rem;
    }
    .form-label .req { color: #DC2626; }

    /* Input brutal */
    .input-brutal {
        background: #F5F7FF;
        color: #090E39;
        border: 2px solid rgba(9,14,57,.15);
        border-radius: .75rem;
        padding: .75rem 1rem;
        font-weight: 600;
        font-size: .875rem;
        transition: border-color .2s, background .2s, box-shadow .2s;
        outline: none;
        width: 100%;
    }
    .input-brutal::placeholder { color: rgba(9,14,57,.4); font-weight: 500; }
    .input-brutal:hover { border-color: rgba(9,14,57,.3); }
    .input-brutal:focus {
        background: #fff;
        border-color: #4382DF;
        box-shadow: 3px 3px 0 #4382DF;
    }

    /* Select custom arrow */
    select.input-brutal {
        appearance: none;
        background-image:
            linear-gradient(45deg, transparent 50%, #090E39 50%),
            linear-gradient(135deg, #090E39 50%, transparent 50%);
        background-position: calc(100% - 20px) calc(50% - 2px), calc(100% - 14px) calc(50% - 2px);
        background-size: 6px 6px, 6px 6px;
        background-repeat: no-repeat;
        padding-right: 2.5rem;
    }

    /* Textarea */
    textarea.input-brutal { resize: vertical; min-height: 8rem; }

    /* File input */
    .file-brutal {
        display: block;
        width: 100%;
        padding: .65rem;
        background: #F5F7FF;
        border: 2px dashed rgba(9,14,57,.25);
        border-radius: .75rem;
        color: #112E81;
        font-weight: 700;
        font-size: .8125rem;
        cursor: pointer;
        transition: border-color .2s, background .2s;
    }
    .file-brutal::file-selector-button {
        background: #4382DF;
        color: #090E39;
        border: 2px solid #090E39;
        border-radius: .55rem;
        padding: .4rem .85rem;
        margin-right: .75rem;
        font-weight: 800;
        font-size: .7rem;
        cursor: pointer;
        transition: background .2s, color .2s;
    }
    .file-brutal::file-selector-button:hover { background: #4647AE; color: #fff; }
    .file-brutal:hover { border-color: #4382DF; background: #EEF3FF; }

    /* Section divider */
    .form-section + .form-section {
        padding-top: 1.25rem;
        border-top: 2px dashed rgba(9,14,57,.1);
    }

    /* Submit btn */
    .btn-3d {
        --ec: #090E39;
        transform: translate(0, 0);
        box-shadow: 3px 3px 0 var(--ec);
        transition: transform .15s ease, box-shadow .15s ease, background .2s, color .2s;
    }
    .btn-3d:hover { transform: translate(2px, 2px); box-shadow: 1px 1px 0 var(--ec); }
    .btn-3d:active { transform: translate(3px, 3px); box-shadow: 0 0 0 var(--ec); }
</style>

<form method="POST" action="{{ $action }}" enctype="multipart/form-data"
      class="bg-white border-2 border-[#090E39] rounded-2xl shadow-[5px_5px_0_#090E39] p-5 sm:p-7 space-y-5">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    {{-- ============================================
         SECTION: INFO DASAR
         ============================================ --}}
    <div class="form-section space-y-5">

        {{-- Title --}}
        <div>
            <label class="form-label">
                {!! $icon('type', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                Judul Event <span class="req">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title', $event?->title) }}" required
                   class="input-brutal"
                   placeholder="Contoh: Neon Night Music Fest 2026">
            @error('title')
                <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                    {!! $icon('alert', 'w-3.5 h-3.5') !!}
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="form-label">
                {!! $icon('file-text', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                Deskripsi <span class="req">*</span>
            </label>
            <textarea name="description" rows="5" required
                      class="input-brutal"
                      placeholder="Ceritakan tentang event, apa yang akan terjadi, siapa yang tampil, dsb...">{{ old('description', $event?->description) }}</textarea>
            @error('description')
                <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                    {!! $icon('alert', 'w-3.5 h-3.5') !!}
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Category & Status --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="form-label">
                    {!! $icon('tag', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                    Kategori <span class="req">*</span>
                </label>
                <select name="category" required class="input-brutal">
                    <option value="">Pilih kategori</option>
                    @foreach (['music' => 'Musik', 'cosplay' => 'Cosplay', 'art' => 'Seni', 'game_tournament' => 'Turnamen Game', 'festival' => 'Festival', 'other' => 'Lainnya'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('category', $event?->category) === $val)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category')
                    <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                        {!! $icon('alert', 'w-3.5 h-3.5') !!}
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="form-label">
                    {!! $icon('activity', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                    Status <span class="req">*</span>
                </label>
                <select name="status" required class="input-brutal">
                    @foreach (['draft' => 'Draft', 'published' => 'Published', 'ongoing' => 'Ongoing', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('status', $event?->status ?? 'draft') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status')
                    <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                        {!! $icon('alert', 'w-3.5 h-3.5') !!}
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>

    {{-- ============================================
         SECTION: LOKASI
         ============================================ --}}
    <div class="form-section space-y-5">

        {{-- Location & Address --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="form-label">
                    {!! $icon('map-pin', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                    Lokasi <span class="req">*</span>
                </label>
                <input type="text" name="location" value="{{ old('location', $event?->location) }}" required
                       class="input-brutal"
                       placeholder="Jakarta Convention Center">
                @error('location')
                    <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                        {!! $icon('alert', 'w-3.5 h-3.5') !!}
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <div>
                <label class="form-label">
                    {!! $icon('map-pin', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                    Alamat Lengkap
                </label>
                <input type="text" name="address" value="{{ old('address', $event?->address) }}"
                       class="input-brutal"
                       placeholder="Jl. Gatot Subroto No.1, Jakarta">
                @error('address')
                    <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                        {!! $icon('alert', 'w-3.5 h-3.5') !!}
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        {{-- Coordinates --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="form-label">
                    {!! $icon('compass', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                    Latitude
                </label>
                <input type="number" step="0.0000001" name="latitude" value="{{ old('latitude', $event?->latitude) }}"
                       class="input-brutal"
                       placeholder="-6.2146">
                @error('latitude')
                    <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                        {!! $icon('alert', 'w-3.5 h-3.5') !!}
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <div>
                <label class="form-label">
                    {!! $icon('compass', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                    Longitude
                </label>
                <input type="number" step="0.0000001" name="longitude" value="{{ old('longitude', $event?->longitude) }}"
                       class="input-brutal"
                       placeholder="106.8009">
                @error('longitude')
                    <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                        {!! $icon('alert', 'w-3.5 h-3.5') !!}
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>

    {{-- ============================================
         SECTION: JADWAL
         ============================================ --}}
    <div class="form-section space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="form-label">
                    {!! $icon('calendar', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                    Tanggal Mulai <span class="req">*</span>
                </label>
                <input type="datetime-local" name="start_date"
                       value="{{ old('start_date', $event?->start_date?->format('Y-m-d\TH:i')) }}" required
                       class="input-brutal">
                @error('start_date')
                    <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                        {!! $icon('alert', 'w-3.5 h-3.5') !!}
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <div>
                <label class="form-label">
                    {!! $icon('calendar', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                    Tanggal Selesai <span class="req">*</span>
                </label>
                <input type="datetime-local" name="end_date"
                       value="{{ old('end_date', $event?->end_date?->format('Y-m-d\TH:i')) }}" required
                       class="input-brutal">
                @error('end_date')
                    <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                        {!! $icon('alert', 'w-3.5 h-3.5') !!}
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <div>
                <label class="form-label">
                    {!! $icon('calendar-clock', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                    Deadline Registrasi
                </label>
                <input type="datetime-local" name="registration_deadline"
                       value="{{ old('registration_deadline', $event?->registration_deadline?->format('Y-m-d\TH:i')) }}"
                       class="input-brutal">
                @error('registration_deadline')
                    <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                        {!! $icon('alert', 'w-3.5 h-3.5') !!}
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>

    {{-- ============================================
         SECTION: BANNER & KAPASITAS
         ============================================ --}}
    <div class="form-section grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Banner --}}
        <div>
            <label class="form-label">
                {!! $icon('image', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                Banner Event
            </label>

            @if ($event?->banner_image)
                <div class="mb-2 bg-[#F5F7FF] border-2 border-[#090E39]/15 rounded-xl p-2">
                    <img src="{{ Storage::url($event->banner_image) }}"
                         class="h-24 w-full object-cover rounded-lg border-2 border-[#090E39]/15"
                         alt="Banner">
                    <p class="inline-flex items-center gap-1 text-[11px] text-[#090E39]/60 mt-1.5 px-1 font-bold">
                        {!! $icon('sparkles', 'w-3 h-3 text-[#4382DF]') !!}
                        Banner saat ini. Upload baru untuk mengganti.
                    </p>
                </div>
            @endif

            <input type="file" name="banner_image" accept="image/*" class="file-brutal">

            <p class="inline-flex items-center gap-1 text-[11px] text-[#090E39]/55 mt-1.5 font-bold">
                {!! $icon('upload-cloud', 'w-3 h-3 text-[#4382DF]') !!}
                Format: JPG, PNG, WebP. Maks 2MB.
            </p>

            @error('banner_image')
                <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                    {!! $icon('alert', 'w-3.5 h-3.5') !!}
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Max participants --}}
        <div>
            <label class="form-label">
                {!! $icon('users', 'w-3.5 h-3.5 text-[#4382DF]') !!}
                Max Peserta
            </label>

            <input type="number" name="max_participants" value="{{ old('max_participants', $event?->max_participants) }}"
                   class="input-brutal"
                   placeholder="5000">

            <p class="inline-flex items-center gap-1 text-[11px] text-[#090E39]/55 mt-1.5 font-bold">
                {!! $icon('users', 'w-3 h-3 text-[#4382DF]') !!}
                Kosongkan jika tidak ada batas.
            </p>

            @error('max_participants')
                <p class="inline-flex items-center gap-1.5 text-xs text-red-600 mt-1.5 font-bold">
                    {!! $icon('alert', 'w-3.5 h-3.5') !!}
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    {{-- ============================================
         ACTIONS
         ============================================ --}}
    <div class="flex flex-col-reverse sm:flex-row gap-3 pt-5 border-t-2 border-dashed border-[#090E39]/10">
        <a href="{{ route('admin.events.index') }}"
           class="inline-flex items-center justify-center gap-2 bg-white text-[#090E39] px-6 py-2.5 rounded-xl font-extrabold text-sm border-2 border-[#090E39]/20 hover:bg-[#090E39] hover:text-white hover:border-[#090E39] transition">
            {!! $icon('x', 'w-4 h-4') !!}
            Batal
        </a>

        <button type="submit"
                class="btn-3d inline-flex items-center justify-center gap-2 bg-[#4647AE] text-white px-6 py-2.5 rounded-xl font-extrabold text-sm border-2 border-[#090E39] hover:bg-[#4382DF] flex-1 sm:flex-none">
            {!! $icon('save', 'w-4 h-4') !!}
            {{ $submitLabel }}
        </button>
    </div>
</form>