<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliahs';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'deskripsi',
        'sks',
        'semester',
        'dosen_id',
    ];

    public function approvedStudents()
    {
        return $this->belongsToMany(User::class, 'course_user', 'mata_kuliah_id', 'user_id')
                    ->wherePivot('status', 'approved');
    }

    // Relasi ke Dosen Pengampu
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    // Relasi ke Mahasiswa yang mengambil mata kuliah (Many-to-Many)
    public function mahasiswas()
    {
        return $this->belongsToMany(User::class, 'course_user', 'mata_kuliah_id', 'user_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // Relasi ke Materi Pembelajaran (One-to-Many)
    public function materials()
    {
        return $this->hasMany(Material::class, 'mata_kuliah_id');
    }

    // Relasi ke Tugas / Assignments (One-to-Many)
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'mata_kuliah_id');
    }
}