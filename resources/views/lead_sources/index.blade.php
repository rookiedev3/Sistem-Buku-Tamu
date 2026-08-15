@extends('layouts.frontoffice')

@section('content')

{{-- Navigasi Tab Master Data (Responsif) --}}
<div class="d-flex gap-2 border-bottom pb-3 mb-4" style="overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch;">
    <a href="{{ route('branches.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('branches*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('branches*') ? '#013220' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Branches
    </a>
    
    <a href="{{ route('products.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('products*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('products*') ? '#013220' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Products
    </a>
    
    <a href="{{ route('lead-sources.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('lead-sources*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('lead-sources*') ? '#013220' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Lead Sources
    </a>
    
    <a href="{{ route('visit-purposes.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('visit-purposes*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('visit-purposes*') ? '#013220' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Visit Purposes
    </a>
    
    <a href="{{ route('guest-categories.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('guest-categories*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('guest-categories*') ? '#013220' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Guest Categories
    </a>
</div>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 20px; font-weight: 800; color: #172033; margin: 0 0 4px 0;">Daftar Lead Sources</h1>
        <p style="font-size: 13px; color: #778195; margin: 0;">Kelola dan pantau seluruh data sumber lead perusahaan.</p>
    </div>
    
    <a href="{{ route('lead-sources.create') }}" style="background: #013220; color: #fff; padding: 11px 18px; border-radius: 12px; font-size: 13px; font-weight: 800; text-decoration: none; display: flex; align-items: center; gap: 6px; box-shadow: 0 8px 20px rgba(0,107,63,.2); border: none; cursor: pointer; white-space: nowrap;">
        + Tambah Lead Sources
    </a>
</div>

{{-- Alert Notifikasi --}}
@if (session('success'))
    <div style="background: #e6f4ea; border: 1px solid #ceebd6; color: #013220; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 600; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

<div style="background: #ffffff; border: 1px solid #e8edf5; border-radius: 20px; box-shadow: 0 18px 50px rgba(31,53,97,.12); overflow: hidden;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; flex-wrap: wrap; gap: 12px;">
        <input type="text" id="searchLeadSource" placeholder="Cari sumber lead..." onkeyup="filterLeadSourceTable()" style="padding: 10px 14px; border: 1px solid #e8edf5; border-radius: 10px; font-size: 13px; width: 100%; max-width: 300px; outline: none; background: #fff; color: #172033; box-sizing: border-box;">
        <div style="font-size: 13px; color: #778195; font-weight: 600;">
            Total Lead Sources: <strong style="color: #172033; font-weight: 800;">{{ $lead_sources->count() }} Sumber</strong>
        </div>
    </div>

    {{-- Wadah overflow-x agar tabel aman di layar HP / Tablet --}}
    <div style="overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch;">
        <table id="leadSourceTable" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; min-width: 600px;">
            <thead>
                <tr style="background: #f8fafc; color: #778195; border-bottom: 1px solid #e8edf5;">
                    <th style="padding: 14px 20px; font-weight: 800; width: 60px;">No</th>
                    <th style="padding: 14px 20px; font-weight: 800;">Nama Sumber Lead</th>
                    <th style="padding: 14px 20px; font-weight: 800; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="color: #172033;">
                @forelse ($lead_sources as $index => $lead_src)
                <tr style="border-bottom: 1px solid #f1f4f9;">
                    <td class="row-number" style="padding: 16px 20px; font-weight: 700;">{{ $index + 1 }}</td>
                    <td style="padding: 16px 20px; font-weight: 800;">{{ $lead_src->name }}</td>
                    <td style="padding: 16px 20px; text-align: center;">
                        <div style="display: flex; justify-content: center; align-items: center; gap: 8px; flex-wrap: wrap;">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('lead-sources.edit', $lead_src->id) }}" style="background: #e8f8f1; color: #013220; padding: 6px 12px; border-radius: 8px; text-decoration: none; font-weight: 800; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-pencil-fill" style="font-size: 11px;"></i> Edit
                            </a>

                            {{-- Tombol Hapus memicu Modal --}}
                            <button type="button" onclick="confirmDelete('{{ $lead_src->id }}', '{{ addslashes($lead_src->name) }}')" style="background: #fef2f2; border: none; color: #e5484d; padding: 6px 12px; border-radius: 8px; font-weight: 800; cursor: pointer; font-size: 12px; font-family: inherit; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-trash-fill" style="font-size: 11px;"></i> Hapus
                            </button>

                            {{-- Form Tersembunyi Hapus --}}
                            <form id="delete-form-{{ $lead_src->id }}" action="{{ route('lead-sources.destroy', $lead_src->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="padding: 32px 20px; text-align: center; color: #778195; font-size: 13px;">
                        Belum ada data Lead Sources.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 16px 24px; border-top: 1px solid #e8edf5; display: flex; justify-content: space-between; align-items: center; background: #fbfcfe; font-size: 12px; color: #778195; flex-wrap: wrap; gap: 8px;">
        <span>Menampilkan {{ $lead_sources->count() }} data lead sources</span>
    </div>

</div>

{{-- MODAL KONFIRMASI HAPUS --}}
<div id="deleteConfirmModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 1000; padding: 16px; box-sizing: border-box;">
    <div style="background: #ffffff; width: 100%; max-width: 400px; padding: 28px; border-radius: 20px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1); text-align: center; box-sizing: border-box;">

        <h3 style="font-size: 17px; font-weight: 800; color: #172033; margin: 0 0 8px 0;">Hapus Lead Source?</h3>
        <p style="font-size: 13px; color: #64748b; margin: 0 0 24px 0; line-height: 1.5;">
            Apakah Anda yakin ingin menghapus sumber lead <strong id="deleteLeadSourceName" style="color: #172033;">-</strong>? Tindakan ini tidak dapat dibatalkan.
        </p>

        <div style="display: flex; gap: 10px;">
            <button type="button" onclick="closeDeleteModal()" style="flex: 1; background: #f1f5f9; color: #475569; border: none; padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                Batal
            </button>
            <button type="button" id="confirmDeleteSubmitBtn" style="flex: 1; background: #dc2626; color: #ffffff; border: none; padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

{{-- Script JavaScript untuk Modal dan Pencarian Real-Time --}}
<script>
    let activeDeleteLeadSourceId = null;

    function confirmDelete(leadSourceId, leadSourceName) {
        activeDeleteLeadSourceId = leadSourceId;
        document.getElementById('deleteLeadSourceName').innerText = leadSourceName;
        document.getElementById('deleteConfirmModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        activeDeleteLeadSourceId = null;
        document.getElementById('deleteConfirmModal').style.display = 'none';
    }

    document.getElementById('confirmDeleteSubmitBtn').addEventListener('click', function() {
        if (activeDeleteLeadSourceId) {
            document.getElementById('delete-form-' + activeDeleteLeadSourceId).submit();
        }
    });

    function filterLeadSourceTable() {
        const input = document.getElementById('searchLeadSource');
        const filter = input.value.toLowerCase().trim();
        const table = document.getElementById('leadSourceTable');
        const tr = table.getElementsByTagName('tr');
        let visibleIndex = 1;

        for (let i = 1; i < tr.length; i++) {
            if (tr[i].getElementsByTagName('td').length <= 1) continue;

            let tdName = tr[i].getElementsByTagName('td')[1];
            let tdNum = tr[i].querySelector('.row-number');

            if (tdName) {
                let txtName = tdName.textContent || tdName.innerText;

                if (txtName.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    if (tdNum) {
                        tdNum.textContent = visibleIndex;
                        visibleIndex++;
                    }
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>

@endsection