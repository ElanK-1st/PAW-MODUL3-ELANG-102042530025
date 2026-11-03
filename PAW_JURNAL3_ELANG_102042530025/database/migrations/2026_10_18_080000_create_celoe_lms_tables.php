<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Mata Kuliah
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mk')->unique();
            $table->string('nama_mk');
            $table->text('deskripsi')->nullable();
            $table->integer('sks')->default(3);
            $table->string('semester')->nullable();
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 2. Tabel Pivot Mahasiswa mengambil Mata Kuliah
        Schema::create('course_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 3. Tabel Materi Pembelajaran
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        // 4. Tabel Tugas (Assignments)
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi');
            $table->dateTime('deadline');
            $table->timestamps();
        });

        // 5. Tabel Pengumpulan Tugas (Submissions)
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('file_path')->nullable();
            $table->text('catatan')->nullable();
            $table->integer('nilai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('course_user');
        Schema::dropIfExists('mata_kuliahs');
    }
};