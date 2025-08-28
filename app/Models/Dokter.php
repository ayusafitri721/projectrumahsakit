<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $fillable = [
        'idDokter',
        'namaDokter', 
        'tanggalLahir',
        'spesialis',
        // 'ruangan_id', // HAPUS INI - kolom tidak ada di database
        'lokasiPraktik', // Menyimpan nama ruangan langsung
        'jamPraktik'
    ];

    protected $dates = [
        'tanggalLahir'
    ];

    // Method untuk mendapatkan data ruangan berdasarkan lokasiPraktik
    public function getRuanganAttribute()
    {
        return \App\Models\Ruangan::where('namaRuangan', $this->lokasiPraktik)->first();
    }

    // Relasi ke model Pasien (hasMany - dokter menangani banyak pasien)
    public function pasiens()
    {
        return $this->hasMany(Pasien::class, 'idDokter', 'id');
    }

    // Method untuk mendapatkan info ruangan lengkap
    public function getInfoRuanganLengkapAttribute()
    {
        $ruangan = $this->ruangan;
        if ($ruangan) {
            return [
                'kode' => $ruangan->kodeRuangan,
                'nama' => $ruangan->namaRuangan,
                'lokasi' => $ruangan->lokasi,
                'dayaTampung' => $ruangan->dayaTampung,
                'status' => $ruangan->dayaTampung > 0 ? 'tersedia' : 'penuh'
            ];
        }
        return null;
    }

    // Method untuk cek apakah ruangan dokter tersedia
    public function isRuanganTersedia()
    {
        $ruangan = $this->ruangan;
        return $ruangan && $ruangan->dayaTampung > 0;
    }

    // Scope untuk filter berdasarkan spesialis
    public function scopeBySpesialis($query, $spesialis)
    {
        return $query->where('spesialis', $spesialis);
    }

    // Method untuk format tampilan di dropdown
    public function getFormatDropdownAttribute()
    {
        $ruanganStatus = $this->isRuanganTersedia() ? 'Tersedia' : 'Penuh';
        return "{$this->namaDokter} - {$this->spesialis} ({$this->lokasiPraktik} - {$ruanganStatus})";
    }
    
}