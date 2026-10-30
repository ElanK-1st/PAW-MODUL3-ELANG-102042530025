@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Detail Mata Kuliah -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6 border-l-4 border-red-600">
        <div class="flex justify-between items-start">
            <div>
                <span class="bg-red-100 text-red-700 text-sm font-semibold px-3 py-1 rounded-full">
                    {{ $course->kode_mk }} • {{ $course->sks }} SKS
                </span>
                <h1 class="text-3xl font-bold text-gray-800 mt-2">{{ $course->nama_mk }}</h1>
                <p class="text-gray-600 mt-1">Dosen Pengampu: <strong>{{ $course->dosen->name ?? '-' }}</strong></p>
                <p class="text-gray-700 mt-4">{{ $course->deskripsi }}</p>
            </div>

            @if(auth()->user()->role === 'dosen' && auth()->id() === $course->dosen_id)
                <div class="flex space-x-2">
                    <a href="{{ route('courses.edit', $course->id) }}" 
                       class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 text-sm font-semibold">
                        Edit MK
                    </a>
                    <form action="{{ route('courses.destroy', $course->id) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-semibold">
                            Hapus MK
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Bagian Materi Pembelajaran -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-xl font-bold text-gray-800">Materi Pembelajaran</h2>
                @if(auth()->user()->role === 'dosen')
                    <a href="{{ route('materials.create', $course->id) }}" 
                       class="px-3 py-1.5 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                        + Tambah Materi
                    </a>
                @endif
            </div>

            @forelse($course->materials as $material)
                <div class="border rounded-lg p-4 mb-3 hover:bg-gray-50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $material->judul }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $material->deskripsi }}</p>
                        @if($material->file_path)
                            <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" 
                               class="inline-block mt-2 text-xs text-blue-600 hover:underline font-semibold">
                                Unduh File Materi
                            </a>
                        @endif
                    </div>

                    @if(auth()->user()->role === 'dosen')
                        <div class="flex space-x-2">
                            <a href="{{ route('materials.edit', [$course->id, $material->id]) }}" 
                               class="text-sm text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('materials.destroy', [$course->id, $material->id]) }}" method="POST"
                                  onsubmit="return confirm('Hapus materi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:underline">Hapus</button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-gray-500 text-sm italic">Belum ada materi pembelajaran yang diunggah.</p>
            @endforelse
        </div>

        <!-- Bagian Tugas Perkuliahan -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-xl font-bold text-gray-800">Tugas Perkuliahan</h2>
                @if(auth()->user()->role === 'dosen')
                    <a href="{{ route('assignments.create', $course->id) }}" 
                       class="px-3 py-1.5 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                        + Buat Tugas
                    </a>
                @endif
            </div>

            @forelse($course->assignments as $assignment)
                <div class="border rounded-lg p-4 mb-3 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $assignment->judul }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $assignment->deskripsi }}</p>
                            <span class="inline-block mt-2 text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">
                                Tenggat: {{ \Carbon\Carbon::parse($assignment->deadline)->format('d M Y, H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm italic">Belum ada tugas pada mata kuliah ini.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection