@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-gray-700">Daftar Lead-Sources</h2>
        <a href="{{ route('lead-sources.create') }}"
            class="bg-[#63A0EF] hover:bg-[#4a90e2] text-white px-4 py-2 rounded-md text-sm transition shadow-sm">
            + Tambah Lead-Sources
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
                <th class="px-3 py-2 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($lead_sources as $lead_src)
                <tr>
                    <td class="px-3 py-2">{{ $lead_src->name }}</td>
        
                    <td class="px-3 py-2 text-right">
                        <a href="{{ route('lead-sources.edit', $lead_src->id) }}"
                            class="text-blue-600 hover:underline text-sm mr-2">Edit</a>
                        <form action="{{ route('lead-sources.destroy', $lead_src->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Yakin ingin menghapus Lead Sources ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-3 py-4 text-center text-gray-400">Belum ada data Lead Sources.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection