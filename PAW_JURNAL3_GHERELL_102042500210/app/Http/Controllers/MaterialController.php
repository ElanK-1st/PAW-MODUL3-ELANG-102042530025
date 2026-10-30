<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    // Menampilkan form tambah materi
    public function create(MataKuliah $course)
    {
        return view('materials.create', compact('course'));
    }

    // Menyimpan materi baru
    public function store(Request $request, MataKuliah $course)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_materi' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('file_materi')) {
            $filePath = $request->file('file_materi')->store('materials', 'public');
        }

        Material::create([
            'mata_kuliah_id' => $course->id,
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'file_path' => $filePath,
        ]);

        return redirect()->route('courses.show', $course->id)
                         ->with('success', 'Materi pembelajaran berhasil ditambahkan!');
    }

    // Menampilkan form edit materi
    public function edit(MataKuliah $course, Material $material)
    {
        return view('materials.edit', compact('course', 'material'));
    }

    // Memperbarui data materi
    public function update(Request $request, MataKuliah $course, Material $material)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_materi' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ]);

        if ($request->hasFile('file_materi')) {
            // Hapus file lama jika ada
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
            $material->file_path = $request->file('file_materi')->store('materials', 'public');
        }

        $material->update([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'file_path' => $material->file_path,
        ]);

        return redirect()->route('courses.show', $course->id)
                         ->with('success', 'Materi pembelajaran berhasil diperbarui!');
    }

    // Menghapus materi
    public function destroy(MataKuliah $course, Material $material)
    {
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()->route('courses.show', $course->id)
                         ->with('success', 'Materi pembelajaran berhasil dihapus!');
    }
}