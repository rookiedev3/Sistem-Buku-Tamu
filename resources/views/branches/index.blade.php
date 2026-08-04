@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm max-w-5xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-gray-700">Data Branch</h2>
        <a href="{{ route('branches.create') }}"
            class="bg-[#63A0EF] hover:bg-[#4a90e2] text-white px-4 py-2 rounded-md text-sm transition shadow-sm">
            + Tambah
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm p-3 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm p-3 rounded-md mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-hidden rounded-md border border-gray-200">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
                    <th class="px-3 py-2 text-left border-b border-gray-200">No</th>
                    <th class="px-3 py-2 text-left border-b border-gray-200">Kode</th>
                    <th class="px-3 py-2 text-left border-b border-gray-200">Nama</th>
                    <th class="px-3 py-2 text-left border-b border-gray-200">Alamat</th>
                    <th class="px-3 py-2 text-center border-b border-gray-200">Telepon</th>
                    <th class="px-3 py-2 text-center border-b border-gray-200">Status</th>
                    <th class="px-3 py-2 text-center border-b border-gray-200">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($branches as $index => $b)
                <tr class="hover:bg-gray-50 border-b border-gray-100 last:border-0">
                    <td class="px-3 py-2">{{ $index + 1 }}</td>
                    <td class="px-3 py-2">{{ $b->code }}</td>
                    <td class="px-3 py-2">{{ $b->name }}</td>
                    <td class="px-3 py-2">{{ $b->address }}</td>
                    <td class="px-3 py-2 text-center">{{ $b->phone }}</td>
                    <td class="px-3 py-2 text-center">
                        @if ($b->is_active)
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs">Aktif</span>
                        @else
                            <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-xs">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-3 py-2">
                        <div class="flex justify-center items-center gap-2">
                            <a href="{{ route('branches.edit', $b->id) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition">
                                Edit
                            </a>
                            <form action="{{ route('branches.destroy', $b->id) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus Branch ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-3 py-6 text-center text-gray-400">Belum ada data branch.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection