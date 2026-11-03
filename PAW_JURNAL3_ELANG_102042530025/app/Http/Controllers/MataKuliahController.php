<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MataKuliahController extends Controller
{
    // Menampilkan daftar katalog mata kuliah
    public function index()
    {
        $courses = MataKuliah::with('dosen')->latest()->get();
        return view('courses.index', compact('courses'));
    }

    // Menampilkan form tambah mata kuliah (Khusus Dosen)
    public function create()
    {
        return view('courses.create');
    }

    // Menyimpan mata kuliah baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|string|max:20|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'nullable|string|max:50',
        ]);

        $validated['dosen_id'] = Auth::id();

        MataKuliah::create($validated);

        return redirect()->route('courses.index')
                         ->with('success', 'Mata kuliah berhasil ditambahkan!');
    }

    // Menampilkan detail mata kuliah beserta materi dan tugasnya
    public function show(MataKuliah $course)
    {
        $course->load(['dosen', 'materials', 'assignments', 'mahasiswas']);
        return view('courses.show', compact('course'));
    }

    // Menampilkan form edit mata kuliah
    public function edit(MataKuliah $course)
    {
        if (Auth::id() !== $course->dosen_id && Auth::user()->role !== 'dosen') {
            abort(403, 'Anda tidak memiliki akses untuk mengubah mata kuliah ini.');
        }

        return view('courses.edit', compact('course'));
    }

    // Memperbarui data mata kuliah di database
    public function update(Request $request, MataKuliah $course)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|string|max:20|unique:mata_kuliahs,kode_mk,' . $course->id,
            'nama_mk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'nullable|string|max:50',
        ]);

        $course->update($validated);

        return redirect()->route('courses.show', $course->id)
                         ->with('success', 'Data mata kuliah berhasil diperbarui!');
    }

    // Menghapus mata kuliah
    public function destroy(MataKuliah $course)
    {
        $course->delete();

        return redirect()->route('courses.index')
                         ->with('success', 'Mata kuliah berhasil dihapus!');
    }

    // Fitur Mahasiswa mendaftar/mengambil mata kuliah (Opsional jika ada di route)
    public function enroll(MataKuliah $course)
    {
        $user = Auth::user();
        if (!$course->mahasiswas()->where('user_id', $user->id)->exists()) {
            $course->mahasiswas()->attach($user->id, ['status' => 'approved']);
        }

        return redirect()->route('courses.show', $course->id)
                         ->with('success', 'Berhasil mendaftar ke mata kuliah!');
    }
}