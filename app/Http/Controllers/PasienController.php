<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PasienController extends Controller
{
    public function index()
    {
        $pasiens = Pasien::latest()->paginate(10);
        return view('pasien.index', compact('pasiens'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create(Request $request)
    {
        // Ambil penyakit dari spesialis dokter (distinct)
        $penyakitList = Dokter::select('spesialis')->distinct()->get();
        
        // Ambil dokter berdasarkan penyakit jika ada parameter
        $dokters = collect();
        if ($request->has('penyakit') && $request->penyakit) {
            $dokters = Dokter::where('spesialis', $request->penyakit)
                            ->get(['id', 'namaDokter', 'spesialis', 'lokasiPraktik', 'jamPraktik']);
        }
        
        // Ambil semua kamar dengan info daya tampung real-time
        $kamarList = Ruangan::all()->map(function($kamar) {
            // Status kamar berdasarkan daya tampung yang tersisa
            $kamar->jumlah_pasien_sekarang = $kamar->dayaTampung == 0 ? "PENUH" : "Tersedia";
            $kamar->sisa_tempat = $kamar->dayaTampung; // Ini adalah tempat yang tersisa
            $kamar->status = $kamar->dayaTampung <= 0 ? 'penuh' : 'tersedia';
            
            return $kamar;
        });
        
        return view('pasien.create', compact('penyakitList', 'kamarList', 'dokters'));
    }

    public function store(Request $request)
    {
        // CEK DAYA TAMPUNG KAMAR DULU SEBELUM VALIDASI LAINNYA
        $kamar = Ruangan::where('namaRuangan', $request->nomorKamar)->first();
        
        if (!$kamar) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kamar yang dipilih tidak valid!');
        }
        
        // CEK APAKAH KAMAR MASIH PUNYA DAYA TAMPUNG
        if ($kamar->dayaTampung <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Kamar {$request->nomorKamar} sudah PENUH! Daya tampung tersisa: {$kamar->dayaTampung}. Silakan pilih kamar lain.");
        }

        $request->validate([
            'nomorRekamMedis' => 'required|unique:pasiens,nomorRekamMedis',
            'namaPasien' => 'required',
            'tanggalLahir' => 'required|date|before:' . Carbon::now()->toDateString(),
            'jenisKelamin' => 'required|in:Laki-laki,Perempuan',
            'alamatPasien' => 'required',
            'kotaPasien' => 'required',
            'pemakaiPasien' => 'nullable',
            'penyakitPasien' => 'required',
            'idDokter' => 'required|exists:dokters,id',
            'tanggalMasuk' => 'required|date',
            'tanggalKeluar' => 'nullable|date|after_or_equal:tanggalMasuk',
            'nomorKamar' => 'required',
        ]);

        // CEK LAGI SEBELUM MENYIMPAN (DOUBLE CHECK)
        $kamarFresh = Ruangan::where('namaRuangan', $request->nomorKamar)->first();
        
        if ($kamarFresh->dayaTampung <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Maaf, kamar {$request->nomorKamar} baru saja penuh! Silakan refresh halaman dan pilih kamar lain.");
        }

        // Hitung usia otomatis (SEKARANG PASTI BENAR)
        $usiaPasien = $this->hitungUsia($request->tanggalLahir);

        // SIMPAN DATA PASIEN
        $pasienBaru = Pasien::create([
            'nomorRekamMedis' => $request->nomorRekamMedis,
            'namaPasien' => $request->namaPasien,
            'tanggalLahir' => $request->tanggalLahir,
            'usiaPasien' => $usiaPasien,
            'jenisKelamin' => $request->jenisKelamin,
            'alamatPasien' => $request->alamatPasien,
            'kotaPasien' => $request->kotaPasien,
            'pemakaiPasien' => $request->pemakaiPasien,
            'penyakitPasien' => $request->penyakitPasien,
            'idDokter' => $request->idDokter,
            'tanggalMasuk' => $request->tanggalMasuk,
            'tanggalKeluar' => $request->tanggalKeluar,
            'nomorKamar' => $request->nomorKamar,
        ]);
        
        // *** PENTING: UPDATE DAYA TAMPUNG DI DATABASE! ***
        // Kurangi daya tampung kamar sebesar 1 HANYA JIKA PASIEN BELUM KELUAR
        if (!$request->tanggalKeluar || Carbon::parse($request->tanggalKeluar)->isFuture()) {
            $kamar->dayaTampung = $kamar->dayaTampung - 1;
            $kamar->save();
        }
        
        // Hitung sisa tempat setelah pasien baru dan pengurangan daya tampung
        $sisaTempat = $kamar->dayaTampung; // Sekarang ini adalah sisa tempat yang bener
        $pesan = "Data Pasien Berhasil Ditambahkan! Daya tampung kamar {$request->nomorKamar} berkurang menjadi {$kamar->dayaTampung}";
        
        if ($sisaTempat == 0) {
            $pesan .= " (KAMAR SEKARANG PENUH!)";
        } else {
            $pesan .= " (Sisa: {$sisaTempat} tempat)";
        }
        
        return redirect()->route('pasien.index')->with('success', $pesan);
    }

    public function show(Pasien $pasien)
    {
        return view('pasien.show', compact('pasien'));
    }

    public function edit(Pasien $pasien, Request $request)
    {
        // Ambil penyakit dari spesialis dokter (distinct)
        $penyakitList = Dokter::select('spesialis')->distinct()->get();
        
        // Tentukan penyakit yang akan digunakan untuk filter dokter
        // Prioritas: parameter URL -> penyakit pasien saat ini
        $selectedPenyakit = $request->get('penyakit', $pasien->penyakitPasien);
        
        // Ambil dokter berdasarkan penyakit yang dipilih
        $dokters = Dokter::where('spesialis', $selectedPenyakit)
                        ->get(['id', 'namaDokter', 'spesialis', 'lokasiPraktik', 'jamPraktik']);
        
        // Debug: Cek apakah dokter ditemukan
        if ($dokters->isEmpty()) {
            Log::warning("Tidak ada dokter ditemukan untuk spesialis: " . $selectedPenyakit);
            // Fallback: ambil dokter berdasarkan penyakit pasien asli
            $dokters = Dokter::where('spesialis', $pasien->penyakitPasien)
                            ->get(['id', 'namaDokter', 'spesialis', 'lokasiPraktik', 'jamPraktik']);
            
            // Jika masih kosong, ambil semua dokter
            if ($dokters->isEmpty()) {
                $dokters = Dokter::all(['id', 'namaDokter', 'spesialis', 'lokasiPraktik', 'jamPraktik']);
            }
        }
        
        // Ambil semua kamar dengan info daya tampung real-time
        $kamarList = Ruangan::all()->map(function($kamar) use ($pasien) {
            // Jika ini kamar pasien saat ini, tambahkan 1 ke daya tampung
            // (karena pasien akan "dikeluarkan" dari kamar ini jika pindah)
            if ($kamar->namaRuangan == $pasien->nomorKamar) {
                $kamar->dayaTampung_display = $kamar->dayaTampung + 1;
                $kamar->status = ($kamar->dayaTampung + 1) > 0 ? 'tersedia' : 'penuh';
                $kamar->is_current_room = true;
            } else {
                // Untuk kamar lain, tampilkan daya tampung asli
                $kamar->dayaTampung_display = $kamar->dayaTampung;
                $kamar->status = $kamar->dayaTampung > 0 ? 'tersedia' : 'penuh';
                $kamar->is_current_room = false;
            }
            
            return $kamar;
        });
        
        return view('pasien.edit', compact('pasien', 'penyakitList', 'dokters', 'kamarList'));
    }

    public function update(Request $request, Pasien $pasien)
    {
        $kamarLama = $pasien->nomorKamar;
        $kamarBaru = $request->nomorKamar;
        $tanggalKeluarLama = $pasien->tanggalKeluar;
        $tanggalKeluarBaru = $request->tanggalKeluar;
        
        // CEK DAYA TAMPUNG JIKA PINDAH KAMAR
        if ($kamarBaru != $kamarLama) {
            $kamar = Ruangan::where('namaRuangan', $kamarBaru)->first();
            
            if (!$kamar) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Kamar yang dipilih tidak valid!');
            }
            
            // CEK APAKAH KAMAR BARU SUDAH PENUH
            if ($kamar->dayaTampung <= 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Kamar {$kamarBaru} sudah PENUH! Daya tampung tersisa: {$kamar->dayaTampung}. Silakan pilih kamar lain.");
            }
        }

        $request->validate([
            'nomorRekamMedis' => 'required|unique:pasiens,nomorRekamMedis,' . $pasien->id,
            'namaPasien' => 'required',
            'tanggalLahir' => 'required|date|before:' . Carbon::now()->toDateString(),
            'jenisKelamin' => 'required|in:Laki-laki,Perempuan',
            'alamatPasien' => 'required',
            'kotaPasien' => 'required',
            'penyakitPasien' => 'required',
            'idDokter' => 'required|exists:dokters,id',
            'tanggalMasuk' => 'required|date',
            'tanggalKeluar' => 'nullable|date|after_or_equal:tanggalMasuk',
            'nomorKamar' => 'required',
        ]);

        // Hitung ulang usia (SEKARANG PASTI BENAR)
        $usiaPasien = $this->hitungUsia($request->tanggalLahir);

        // *** PERBAIKAN: LOGIKA UPDATE DAYA TAMPUNG BERDASARKAN STATUS KELUAR PASIEN ***
        $hariIni = Carbon::now()->toDateString();
        
        // Cek status keluar pasien SEBELUM dan SESUDAH update
        $statusKeluarSebelum = $this->getStatusKeluar($tanggalKeluarLama, $hariIni);
        $statusKeluarSesudah = $this->getStatusKeluar($tanggalKeluarBaru, $hariIni);
        
        // *** LOGIKA PINDAH KAMAR ***
        if ($kamarBaru != $kamarLama) {
            // KEMBALIKAN daya tampung kamar lama (+1) HANYA JIKA PASIEN MASIH AKTIF DI KAMAR LAMA
            if ($statusKeluarSebelum == 'aktif') {
                $kamarLamaObj = Ruangan::where('namaRuangan', $kamarLama)->first();
                if ($kamarLamaObj) {
                    $kamarLamaObj->dayaTampung = $kamarLamaObj->dayaTampung + 1;
                    $kamarLamaObj->save();
                    Log::info("Kamar lama {$kamarLama} dikembalikan +1, sekarang: {$kamarLamaObj->dayaTampung}");
                }
            }
            
            // KURANGI daya tampung kamar baru (-1) HANYA JIKA PASIEN AKAN AKTIF DI KAMAR BARU
            if ($statusKeluarSesudah == 'aktif') {
                $kamarBaruObj = Ruangan::where('namaRuangan', $kamarBaru)->first();
                if ($kamarBaruObj) {
                    $kamarBaruObj->dayaTampung = $kamarBaruObj->dayaTampung - 1;
                    $kamarBaruObj->save();
                    Log::info("Kamar baru {$kamarBaru} dikurangi -1, sekarang: {$kamarBaruObj->dayaTampung}");
                }
            }
            
            $pesanPindah = " (Pindah dari {$kamarLama} ke {$kamarBaru})";
        } 
        // *** LOGIKA PERUBAHAN STATUS KELUAR DI KAMAR YANG SAMA ***
        else {
            $kamarObj = Ruangan::where('namaRuangan', $kamarLama)->first();
            
            if ($kamarObj) {
                // Jika status berubah dari KELUAR menjadi AKTIF
                if ($statusKeluarSebelum == 'keluar' && $statusKeluarSesudah == 'aktif') {
                    $kamarObj->dayaTampung = $kamarObj->dayaTampung - 1;
                    $kamarObj->save();
                    Log::info("Pasien kembali aktif di {$kamarLama}, daya tampung -1, sekarang: {$kamarObj->dayaTampung}");
                    $pesanPindah = " (Pasien kembali aktif di ruangan)";
                }
                // Jika status berubah dari AKTIF menjadi KELUAR
                elseif ($statusKeluarSebelum == 'aktif' && $statusKeluarSesudah == 'keluar') {
                    $kamarObj->dayaTampung = $kamarObj->dayaTampung + 1;
                    $kamarObj->save();
                    Log::info("Pasien keluar dari {$kamarLama}, daya tampung +1, sekarang: {$kamarObj->dayaTampung}");
                    $pesanPindah = " (Pasien sudah keluar dari ruangan)";
                }
                else {
                    $pesanPindah = "";
                }
            } else {
                $pesanPindah = "";
            }
        }

        // UPDATE DATA PASIEN
        $pasien->update([
            'nomorRekamMedis' => $request->nomorRekamMedis,
            'namaPasien' => $request->namaPasien,
            'tanggalLahir' => $request->tanggalLahir,
            'usiaPasien' => $usiaPasien,
            'jenisKelamin' => $request->jenisKelamin,
            'alamatPasien' => $request->alamatPasien,
            'kotaPasien' => $request->kotaPasien,
            'pemakaiPasien' => $request->pemakaiPasien,
            'penyakitPasien' => $request->penyakitPasien,
            'idDokter' => $request->idDokter,
            'tanggalMasuk' => $request->tanggalMasuk,
            'tanggalKeluar' => $request->tanggalKeluar,
            'nomorKamar' => $request->nomorKamar,
        ]);
        
        return redirect()->route('pasien.index')->with('success', "Data Pasien Berhasil Diupdate{$pesanPindah}");
    }

    public function destroy(Pasien $pasien)
    {
        $namaKamar = $pasien->nomorKamar;
        $tanggalKeluar = $pasien->tanggalKeluar;
        $hariIni = Carbon::now()->toDateString();
        
        // Cek apakah pasien masih aktif di ruangan (belum keluar atau tanggal keluar di masa depan)
        $statusKeluar = $this->getStatusKeluar($tanggalKeluar, $hariIni);
        
        // DELETE PASIEN
        $pasien->delete();
        
        // *** PENTING: KEMBALIKAN DAYA TAMPUNG KAMAR HANYA JIKA PASIEN MASIH AKTIF ***
        if ($statusKeluar == 'aktif') {
            $kamar = Ruangan::where('namaRuangan', $namaKamar)->first();
            if ($kamar) {
                $kamar->dayaTampung = $kamar->dayaTampung + 1;
                $kamar->save();
                
                $pesan = "Data Pasien Berhasil Dihapus! Daya tampung kamar {$namaKamar} dikembalikan menjadi {$kamar->dayaTampung}";
            } else {
                $pesan = "Data Pasien Berhasil Dihapus";
            }
        } else {
            $pesan = "Data Pasien Berhasil Dihapus (Pasien sudah keluar, daya tampung tidak berubah)";
        }
        
        return redirect()->route('pasien.index')->with('success', $pesan);
    }

    // *** METHOD BARU: CEK STATUS KELUAR PASIEN ***
    private function getStatusKeluar($tanggalKeluar, $hariIni)
    {
        if (!$tanggalKeluar || empty($tanggalKeluar)) {
            return 'aktif'; // Pasien belum ada tanggal keluar
        }
        
        $tanggalKeluarCarbon = Carbon::parse($tanggalKeluar);
        $hariIniCarbon = Carbon::parse($hariIni);
        
        if ($tanggalKeluarCarbon->lte($hariIniCarbon)) {
            return 'keluar'; // Pasien sudah keluar (tanggal keluar <= hari ini)
        } else {
            return 'aktif'; // Pasien masih aktif (tanggal keluar > hari ini)
        }
    }

    // *** PERBAIKAN METHOD HITUNG USIA ***
    private function hitungUsia($tanggalLahir)
    {
        $lahir = Carbon::parse($tanggalLahir);
        $sekarang = Carbon::now();
        
        // VALIDASI: Pastikan tanggal lahir tidak di masa depan
        if ($lahir->isAfter($sekarang)) {
            return 0; // Return 0 jika tanggal lahir di masa depan
        }
        
        // Hitung usia dengan logika "tahun sekarang - tahun lahir" dengan penyesuaian
        $usia = $sekarang->year - $lahir->year;
        if ($sekarang->month < $lahir->month || ($sekarang->month == $lahir->month && $sekarang->day < $lahir->day)) {
            $usia--;
        }
        
        return $usia < 0 ? 0 : $usia; // Pastikan usia tidak negatif
    }

    // TAMBAHAN METHOD UNTUK AJAX (JIKA MASIH DIPERLUKAN DI VIEW LAIN)
    public function getDokter($penyakit)
    {
        try {
            $penyakit = urldecode($penyakit);
            $dokters = Dokter::where('spesialis', $penyakit)
                            ->get(['id', 'namaDokter', 'spesialis', 'lokasiPraktik', 'jamPraktik']);
            
            // Format response untuk keperluan JavaScript/AJAX
            $formattedDokters = $dokters->map(function($dokter) {
                return [
                    'id' => $dokter->id,
                    'text' => "{$dokter->namaDokter} - {$dokter->spesialis} ({$dokter->lokasiPraktik} - {$dokter->jamPraktik})",
                    'namaDokter' => $dokter->namaDokter,
                    'spesialis' => $dokter->spesialis,
                    'lokasiPraktik' => $dokter->lokasiPraktik,
                    'jamPraktik' => $dokter->jamPraktik
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $formattedDokters,
                'count' => $formattedDokters->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error loading dokter: ' . $e->getMessage()
            ], 500);
        }
    }

    // *** METHOD BARU UNTUK MENDAPATKAN DATA DOKTER BESERTA RUANGANNYA ***
    public function getDokterWithRoom($dokterId)
    {
        try {
            $dokter = Dokter::find($dokterId);
            
            if (!$dokter) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dokter tidak ditemukan'
                ]);
            }

            // Cari ruangan berdasarkan lokasiPraktik dokter
            $ruangan = Ruangan::where('namaRuangan', $dokter->lokasiPraktik)->first();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'dokter' => [
                        'id' => $dokter->id,
                        'namaDokter' => $dokter->namaDokter,
                        'spesialis' => $dokter->spesialis,
                        'lokasiPraktik' => $dokter->lokasiPraktik,
                        'jamPraktik' => $dokter->jamPraktik
                    ],
                    'ruangan' => $ruangan ? [
                        'id' => $ruangan->id,
                        'kodeRuangan' => $ruangan->kodeRuangan,
                        'namaRuangan' => $ruangan->namaRuangan,
                        'lokasi' => $ruangan->lokasi,
                        'dayaTampung' => $ruangan->dayaTampung,
                        'status' => $ruangan->dayaTampung > 0 ? 'tersedia' : 'penuh'
                    ] : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // TAMBAHAN METHOD UNTUK CEK KAMAR (JIKA MASIH DIPERLUKAN)
    public function cekKamar($namaKamar)
    {
        try {
            $kamar = Ruangan::where('namaRuangan', $namaKamar)->first();
            
            if (!$kamar) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kamar tidak ditemukan'
                ]);
            }

            if ($kamar->dayaTampung <= 0) {
                return response()->json([
                    'status' => 'full',
                    'message' => 'Kamar sudah penuh',
                    'current' => 0,
                    'max' => 0
                ]);
            } else {
                return response()->json([
                    'status' => 'available',
                    'message' => 'Kamar tersedia',
                    'current' => 0,
                    'max' => $kamar->dayaTampung,
                    'available' => $kamar->dayaTampung
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error checking room'], 500);
        }
    }

    // *** METHOD UNTUK CEK KAPASITAS RUANGAN (DIPANGGIL VIA AJAX) ***
    public function getKapasitasRuangan(Request $request)
    {
        $namaRuangan = $request->input('ruangan');
        $hariIni = Carbon::now()->toDateString();
        
        Log::info('Cek kapasitas ruangan:', ['ruangan' => $namaRuangan]);
        
        try {
            // Cari ruangan berdasarkan namaRuangan
            $ruangan = Ruangan::where('namaRuangan', $namaRuangan)->first();
            
            if (!$ruangan) {
                // Coba cari dengan field lokasi jika tidak ketemu
                $ruangan = Ruangan::where('lokasi', $namaRuangan)->first();
            }
            
            if ($ruangan) {
                // *** HITUNG PASIEN AKTIF DENGAN LOGIKA BARU ***
                // Pasien aktif = pasien yang:
                // 1. Tidak ada tanggal keluar ATAU
                // 2. Tanggal keluar masih di masa depan (> hari ini)
                $pasienAktif = Pasien::where('nomorKamar', $namaRuangan)
                                ->where(function($query) use ($hariIni) {
                                    $query->whereNull('tanggalKeluar')
                                          ->orWhere('tanggalKeluar', '')
                                          ->orWhere('tanggalKeluar', '>', $hariIni); // Tanggal keluar di masa depan
                                })
                                ->count();
                
                // PENTING: Gunakan dayaTampung dari database sebagai sisa kapasitas
                $dayaTampungAsli = (int) $ruangan->dayaTampung;
                $tersedia = max(0, $dayaTampungAsli);
                
                // Hitung total kapasitas asli
                $totalKapasitasAsli = $dayaTampungAsli + $pasienAktif;
                
                Log::info("Kapasitas ruangan {$namaRuangan}:", [
                    'pasien_aktif' => $pasienAktif,
                    'daya_tampung_db' => $dayaTampungAsli,
                    'total_kapasitas_asli' => $totalKapasitasAsli,
                    'tersedia' => $tersedia
                ]);
                
                return response()->json([
                    'success' => true,
                    'dayaTampung' => $totalKapasitasAsli,
                    'terisi' => $pasienAktif,
                    'tersedia' => $tersedia,
                    'namaRuangan' => $ruangan->namaRuangan
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Ruangan tidak ditemukan',
                'dayaTampung' => 0,
                'terisi' => 0,
                'tersedia' => 0
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getKapasitasRuangan:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error sistem',
                'dayaTampung' => 0,
                'terisi' => 0, 
                'tersedia' => 0
            ]);
        }
    }

    // *** METHOD BARU: UPDATE OTOMATIS DAYA TAMPUNG BERDASARKAN TANGGAL KELUAR ***
    public function updateOtomatisDayaTampung()
    {
        try {
            $hariIni = Carbon::now()->toDateString();
            
            // Cari semua pasien yang tanggal keluarnya HARI INI dan belum di-update ruangannya
            $pasienKeluarHariIni = Pasien::where('tanggalKeluar', $hariIni)
                                        ->whereNotNull('tanggalKeluar')
                                        ->get();
            
            $totalUpdated = 0;
            $logUpdate = [];
            
            foreach ($pasienKeluarHariIni as $pasien) {
                // Update daya tampung ruangan (+1)
                $ruangan = Ruangan::where('namaRuangan', $pasien->nomorKamar)->first();
                
                if ($ruangan) {
                    $dayaTampungSebelum = $ruangan->dayaTampung;
                    $ruangan->dayaTampung = $ruangan->dayaTampung + 1;
                    $ruangan->save();
                    
                    $totalUpdated++;
                    $logUpdate[] = [
                        'pasien' => $pasien->namaPasien,
                        'ruangan' => $pasien->nomorKamar,
                        'sebelum' => $dayaTampungSebelum,
                        'sesudah' => $ruangan->dayaTampung
                    ];
                    
                    Log::info("Auto-update daya tampung:", $logUpdate[count($logUpdate) - 1]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil update {$totalUpdated} ruangan dari pasien yang keluar hari ini",
                'updated' => $logUpdate
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updateOtomatisDayaTampung:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error sistem: ' . $e->getMessage()
            ]);
        }
    }

    // *** METHOD UNTUK SINKRONISASI DAYA TAMPUNG SECARA MANUAL ***
    public function sinkronisasiDayaTampung()
    {
        try {
            $hariIni = Carbon::now()->toDateString();
            $ruanganUpdated = [];
            
            // Ambil semua ruangan
            $semuaRuangan = Ruangan::all();
            
            foreach ($semuaRuangan as $ruangan) {
                // Hitung pasien aktif di ruangan ini (yang belum keluar atau tanggal keluar masih di masa depan)
                $pasienAktif = Pasien::where('nomorKamar', $ruangan->namaRuangan)
                                    ->where(function($query) use ($hariIni) {
                                        $query->whereNull('tanggalKeluar')
                                              ->orWhere('tanggalKeluar', '')
                                              ->orWhere('tanggalKeluar', '>', $hariIni);
                                    })
                                    ->count();
                
                // Hitung total pasien yang pernah masuk ke ruangan ini
                $totalPasienPernah = Pasien::where('nomorKamar', $ruangan->namaRuangan)->count();
                
                // Asumsi kapasitas asli ruangan (bisa disesuaikan)
                $kapasitasAsliRuangan = $totalPasienPernah + $ruangan->dayaTampung;
                
                // Hitung daya tampung yang seharusnya
                $dayaTampungSeharusnya = $kapasitasAsliRuangan - $pasienAktif;
                
                if ($ruangan->dayaTampung != $dayaTampungSeharusnya) {
                    $dayaTampungSebelum = $ruangan->dayaTampung;
                    $ruangan->dayaTampung = $dayaTampungSeharusnya;
                    $ruangan->save();
                    
                    $ruanganUpdated[] = [
                        'nama' => $ruangan->namaRuangan,
                        'sebelum' => $dayaTampungSebelum,
                        'sesudah' => $dayaTampungSeharusnya,
                        'pasien_aktif' => $pasienAktif,
                        'total_kapasitas' => $kapasitasAsliRuangan
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Sinkronisasi selesai! " . count($ruanganUpdated) . " ruangan diupdate",
                'updated' => $ruanganUpdated
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error sinkronisasiDayaTampung:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error sistem: ' . $e->getMessage()
            ]);
        }
    }
}