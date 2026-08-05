@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm max-w-2xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-gray-700">Edit Guest Categories</h2>
        <a href="{{ route('guest-categories.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            &larr; Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm p-3 rounded-md mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('guest-categories.update', $guest_category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
            <input type="text" name="name" id="name" value="{{ old('name', $guest_category->name) }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#63A0EF]">
        </div>
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
            <input type="text" name="color" id="color" value="{{ old('color', $guest_category->color) }}"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#63A0EF]">
        </div>

        <div class="flex gap-2">
            <button type="submit"
                class="bg-[#63A0EF] hover:bg-[#4a90e2] text-white px-4 py-2 rounded-md text-sm transition shadow-sm">
                Update
            </button>
            <a href="{{ route('guest-categories.index') }}"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection