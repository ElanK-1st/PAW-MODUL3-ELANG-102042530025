@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">Edit Mata Kuliah</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('courses.update', $course->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="kode_mk" class="block text-gray-700 font-semibold mb-2">Kode Mata Kuliah</label>
                <input type="text" name="kode_mk" id="kode_mk" value="{{ old('kode_mk', $course->kode_mk) }}" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-red-600" required>
            </div>

            <div class="mb-4">
                <label for="nama_mk" class="block text-gray-700 font-semibold mb-2">Nama Mata Kuliah</label>
                <input type="text" name="nama_mk" id="nama_mk" value="{{ old('nama_mk', $course->nama_mk) }}" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-red-600" required>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="sks" class="block text-gray-700 font-semibold mb-2">Jumlah SKS</label>
                    <input type="number" name="sks" id="sks" value="{{ old('sks', $course->sks) }}" min="1" max="6"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-red-600" required>
                </div>
                <div>
                    <label for="semester" class="block text-gray-700 font-semibold mb-2">Semester</label>
                    <input type="text" name="semester" id="semester" value="{{ old('semester', $course->semester) }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-red-600">
                </div>
            </div>

            <div class="mb-6">
                <label for="deskripsi" class="block text-gray-700 font-semibold mb-2">Deskripsi Mata Kuliah</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" 
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-red-600">{{ old('deskripsi', $course->deskripsi) }}</textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('courses.show', $course->id) }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
                <button type="submit" class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection