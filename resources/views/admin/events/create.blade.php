@extends('admin.layouts.app')

@section('title', 'Buat Event Baru — Admin')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.events.index') }}" class="text-sm text-nova-600 hover:underline">← Kembali ke daftar event</a>
    <h1 class="text-2xl font-bold text-gray-900 mt-2">Buat Event Baru</h1>
</div>

@include('admin.events._form', [
    'action' => route('admin.events.store'),
    'method' => 'POST',
    'event' => null,
    'submitLabel' => 'Buat Event',
])

@endsection