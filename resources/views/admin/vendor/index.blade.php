@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="content-card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2 class="content-card-title">Data Master Vendor (Perusahaan)</h2>
        <a href="{{ route('admin.vendor.create') }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 14px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16" style="display:inline; margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Tambah Vendor
        </a>
    </div>
    
    <div style="padding: 24px;">
        @if(session('success'))
            <div style="background: #ecfdf5; color: #059669; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 12px; color: #4b5563; font-weight: 600; width: 50px;">No</th>
                    <th style="padding: 12px; color: #4b5563; font-weight: 600;">Nama Vendor</th>
                    <th style="padding: 12px; color: #4b5563; font-weight: 600;">Jabatan / Peran</th>
                    <th style="padding: 12px; color: #4b5563; font-weight: 600; width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendor as $index => $item)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px; color: #374151;">{{ $index + 1 }}</td>
                    <td style="padding: 12px; color: #111827; font-weight: 500;">{{ $item->nama }}</td>
                    <td style="padding: 12px; color: #374151;">{{ $item->jabatan ?? '-' }}</td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="{{ route('admin.vendor.edit', $item->id) }}" style="color: #3b82f6; margin-right: 12px; font-weight: 500; text-decoration: none;">Edit</a>
                        <form action="{{ route('admin.vendor.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data vendor ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #ef4444; background: none; border: none; font-weight: 500; cursor: pointer;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 24px; text-align: center; color: #6b7280;">Belum ada data Vendor.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
