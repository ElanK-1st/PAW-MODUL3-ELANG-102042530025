@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Transaksi Peminjaman Buku</h2>
        <p class="text-muted mb-0">Catat peminjaman buku oleh anggota perpustakaan.</p>
    </div>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary">
        &larr; Kembali
    </a>
</div>

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm bg-white p-4">
    <form action="{{ route('peminjaman.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="anggota_id" class="form-label fw-semibold">Pilih Anggota <span class="text-danger">*</span></label>
            <select name="anggota_id" id="anggota_id" class="form-select @error('anggota_id') is-invalid @enderror" required>
                <option value="">-- Pilih Anggota Peminjam --</option>
                @foreach ($anggota as $a)
                    <option value="{{ $a->id }}" {{ old('anggota_id') == $a->id ? 'selected' : '' }}>
                        {{ $a->nama }} - {{ $a->email }}
                    </option>
                @endforeach
            </select>
            @error('anggota_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="buku_id" class="form-label fw-semibold">Pilih Buku <span class="text-danger">*</span></label>
            <select name="buku_id" id="buku_id" class="form-select @error('buku_id') is-invalid @enderror" required>
                <option value="">-- Pilih Buku yang Dipinjam --</option>
                @foreach ($buku as $b)
                    <option value="{{ $b->id }}" {{ old('buku_id') == $b->id ? 'selected' : '' }}>
                        {{ $b->judul }} (Stok: {{ $b->stok }})
                    </option>
                @endforeach
            </select>
            <div class="form-text text-muted">Hanya buku dengan stok > 0 yang muncul dalam pilihan. Stok akan otomatis berkurang 1.</div>
            @error('buku_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tanggal_pinjam" class="form-label fw-semibold">Tanggal Pinjam <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                   value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
            @error('tanggal_pinjam')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('peminjaman.index') }}" class="btn btn-light">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Peminjaman</button>
        </div>
    </form>
</div>
@endsection