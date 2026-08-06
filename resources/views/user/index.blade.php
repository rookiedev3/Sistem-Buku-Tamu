@extends('layouts.app') {{-- Sesuaikan dengan layout kamu --}}

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manajemen Pengguna</h3>
        <a href="{{ route('user.create') }}" class="btn btn-primary">+ Tambah User</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email / Phone</th>
                <th>Role</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $u)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }} <br><small class="text-muted">{{ $u->phone ?? '-' }}</small></td>
                <td><span class="badge bg-info text-dark">{{ strtoupper($u->role) }}</span></td>
                <td>
                    <span class="badge {{ $u->is_active ? 'bg-success' : 'bg-danger' }}">
                        {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('user.edit', $u->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    
                    <form action="{{ route('user.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection