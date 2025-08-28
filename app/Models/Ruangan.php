<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    // Nama tabel yang benar (tanpa 's')
    protected $table = 'ruangan';

    protected $fillable = [
        'kodeRuangan',
        'namaRuangan',
        'dayaTampung',
        'lokasi'
    ];

    // Relationship dengan Pasien
    public function pasiens()
    {
        return $this->hasMany(Pasien::class, 'nomorKamar', 'namaRuangan');
    }

    // Relationship dengan Dokter berdasarkan namaRuangan
    public function dokters()
    {
        return $this->hasMany(Dokter::class, 'lokasiPraktik', 'namaRuangan');
    }

    // Method untuk cek ketersediaan ruangan (berdasarkan pasien yang belum keluar)
    public function getKetersediaanAttribute()
    {
        $terisi = $this->pasiens()->whereNull('tanggalKeluar')->count();
        return $this->dayaTampung - $terisi;
    }

    // Method untuk cek apakah ruangan penuh
    public function getIsPenuhAttribute()
    {
        return $this->ketersediaan <= 0;
    }
}