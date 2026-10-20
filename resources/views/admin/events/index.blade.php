@extends('admin.layouts.app')

@section('title', 'Manage Events — Admin')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manage Events</h1>
        <p class="text-sm text-gray-500 mt-1">Total: {{ $events->total() }} event</p>
    </div>
    <a href="{{ route('admin.events.create') }}"
       class="inline-flex items-center gap-2 bg-nova-600 hover:bg-nova-700 text-white px-4 py-2.5 rounded-lg font-medium transition">
        <span>+</span> Buat Event Baru
    </a>
</div>

{{-- Filter --}}
<form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6 flex flex-col sm:flex-row gap-3">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari event..."
           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">
    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nova-500 outline-none">
        <option value="">Semua Status</option>
        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
        <option value="published" @selected(request('status') === 'published')>Published</option>
        <option value="ongoing" @selected(request('status') === 'ongoing')>Ongoing</option>
        <option value="completed" @selected(request('status') === 'completed')>Completed</option>
        <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
    </select>
    <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-lg hover:bg-gray-900 transition">
        Filter
    </button>
</form>

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-700">Event</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-700">Kategori</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-700">Tanggal</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-700">Status</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($events as $event)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $event->title }}</div>
                            <div class="text-xs text-gray-500">{{ $event->location }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded capitalize">
                                {{ $event->category }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ $event->start_date->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusColor = match($event->status) {
                                    'published' => 'bg-green-100 text-green-700',
                                    'draft' => 'bg-gray-100 text-gray-700',
                                    'ongoing' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-purple-100 text-purple-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="text-xs px-2 py-1 rounded capitalize {{ $statusColor }}">
                                {{ $event->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex gap-2">
                                <a href="{{ route('admin.events.show', $event) }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Detail</a>
                                <a href="{{ route('admin.events.edit', $event) }}" class="text-xs text-nova-600 hover:text-nova-700 font-medium">Edit</a>
                                <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                                      onsubmit="return confirm('Yakin hapus event ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs text-red-600 hover:text-red-700 font-medium">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-gray-500">
                            Belum ada event. <a href="{{ route('admin.events.create') }}" class="text-nova-600 hover:underline">Buat event pertama</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
<div class="mt-4">
    {{ $events->links() }}
</div>

@endsection