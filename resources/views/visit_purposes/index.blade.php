@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-gray-700">Daftar Visit-Purposes</h2>
        <a href="{{ route('visit-purposes.create') }}"
            class="bg-[#63A0EF] hover:bg-[#4a90e2] text-white px-4 py-2 rounded-md text-sm transition shadow-sm">
            + Tambah Visit Purposes
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm p-3 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 border-b">
            <tr>
                <th class="px-3 py-2">Nama</th>
                <th class="px-3 py-2">Status</th>
                <th class="px-3 py-2 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($visit_purposes as $vst_purposes)
                <tr>
                    <td class="px-3 py-2">{{ $vst_purposes->name }}</td>
                    <td class="px-3 py-2">
                        @if ($vst_purposes->is_active)
                            <span class="text-green-600 text-xs font-medium">Aktif</span>
                        @else
                            <span class="text-gray-400 text-xs font-medium">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-right">
                        <a href="{{ route('visit-purposes.edit', $vst_purposes->id) }}"
                            class="text-blue-600 hover:underline text-sm mr-2">Edit</a>
                        <form action="{{ route('visit-purposes.destroy', $vst_purposes->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Yakin ingin menghapus Visit Purposes ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-3 py-4 text-center text-gray-400">Belum ada data Visit Purposes.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection