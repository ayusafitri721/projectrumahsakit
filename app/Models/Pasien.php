<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasiens';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nomorRekamMedis',
        'namaPasien',
        'tanggalLahir',
        'jenisKelamin',
        'alamatPasien',
        'kotaPasien',
        'usiaPasien',
        // 'pemakaiPasien', // TAMBAH INI - field yang hilang
        'penyakitPasien',
        'idDokter',
        'tanggalMasuk',
        'tanggalKeluar',
        'nomorKamar'
    ];

    protected $casts = [
        'tanggalLahir' => 'date',
        'tanggalMasuk' => 'date',
        'tanggalKeluar' => 'date'
    ];

    // PERBAIKAN UTAMA: Relasi ke Dokter
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'idDokter', 'id');
        // Jika primary key dokter adalah 'idDokter', gunakan ini:
        // return $this->belongsTo(Dokter::class, 'idDokter', 'idDokter');
    }

    // Method untuk menghitung usia otomatis
    public static function hitungUsia($tanggalLahir)
    {
        return Carbon::parse($tanggalLahir)->age;
    }

    // Method untuk cek daya tampung kamar
    public static function cekDayaTampungKamar($nomorKamar)
    {
        // Ambil data ruangan berdasarkan nama kamar
        $ruangan = \App\Models\Ruangan::where('namaRuangan', $nomorKamar)->first();
        
        if (!$ruangan) {
            return ['status' => 'not_found', 'message' => 'Kamar tidak ditemukan'];
        }

        // Hitung jumlah pasien yang sedang di kamar tersebut (belum keluar)
        $jumlahPasienDiKamar = self::where('nomorKamar', $nomorKamar)
            ->whereNull('tanggalKeluar')
            ->count();

        $dayaTampung = $ruangan->dayaTampung;
        $sisaKapasitas = $dayaTampung - $jumlahPasienDiKamar;

        if ($sisaKapasitas <= 0) {
            return [
                'status' => 'full', 
                'message' => 'Daya tampung kamar penuh',
                'current' => $jumlahPasienDiKamar,
                'max' => $dayaTampung
            ];
        }

        return [
            'status' => 'available',
            'message' => 'Kamar tersedia',
            'current' => $jumlahPasienDiKamar,
            'max' => $dayaTampung,
            'sisa' => $sisaKapasitas
        ];
    }

    // Method accessor untuk mendapatkan nama dokter dengan aman
    public function getNamaDokterAttribute()
    {
        return $this->dokter ? $this->dokter->namaDokter : "Dokter ID: {$this->idDokter} (Tidak ditemukan)";
    }

    // Method untuk cek status pasien
    public function getStatusPasienAttribute()
    {
        return $this->tanggalKeluar ? 'Keluar' : 'Rawat Inap';
    }

    // Scope untuk pasien yang masih rawat inap
    public function scopeRawatInap($query)
    {
        return $query->whereNull('tanggalKeluar');
    }

    // Scope untuk pasien yang sudah keluar
    public function scopeSudahKeluar($query)
    {
        return $query->whereNotNull('tanggalKeluar');
    }

    
}