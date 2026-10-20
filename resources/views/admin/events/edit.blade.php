@extends('admin.layouts.app')

@section('title', 'Edit Event — Admin')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.events.index') }}" class="text-sm text-nova-600 hover:underline">← Kembali ke daftar event</a>
    <h1 class="text-2xl font-bold text-gray-900 mt-2">Edit Event: {{ $event->title }}</h1>
</div>

@include('admin.events._form', [
    'action' => route('admin.events.update', $event),
    'method' => 'PUT',
    'event' => $event,
    'submitLabel' => 'Simpan Perubahan',
])

@endsection