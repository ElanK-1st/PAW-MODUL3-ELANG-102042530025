<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\MataKuliah;
use App\Models\Material;
use App\Models\Assignment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Dosen
        $dosen = User::create([
            'name' => 'Dr. Ir. Budi Santoso, M.T.',
            'email' => 'dosen@telkomuniversity.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'dosen',
        ]);

        // 2. Buat Akun Mahasiswa
        $mahasiswa = User::create([
            'name' => 'Ahmad Raihan Arifinza',
            'email' => 'mahasiswa@telkomuniversity.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
        ]);

        // 3. Buat Data Awal Mata Kuliah
        $mk = MataKuliah::create([
            'kode_mk' => 'PAW101',
            'nama_mk' => 'Pengembangan Aplikasi Website',
            'deskripsi' => 'Mata kuliah yang membahas pembuatan aplikasi web modern menggunakan framework Laravel dan operasi CRUD.',
            'sks' => 3,
            'semester' => 'Ganjil 2026/2027',
            'dosen_id' => $dosen->id,
        ]);

        // 4. Daftarkan Mahasiswa ke Mata Kuliah
        $mk->mahasiswas()->attach($mahasiswa->id, ['status' => 'approved']);

        // 5. Buat Contoh Materi
        Material::create([
            'mata_kuliah_id' => $mk->id,
            'judul' => 'Modul 3 - Operasi CRUD dengan Laravel',
            'deskripsi' => 'Materi pengenalan Eloquent ORM, Migration, Seeder, dan Controller di Laravel.',
            'file_path' => null,
        ]);

        // 6. Buat Contoh Tugas
        Assignment::create([
            'mata_kuliah_id' => $mk->id,
            'judul' => 'Jurnal Modul 3: Implementasi CRUD CeLOE LMS',
            'deskripsi' => 'Lengkapi fitur CRUD Mata Kuliah dan Materi pada proyek CeLOE LMS.',
            'deadline' => now()->addDays(7),
        ]);
    }
}