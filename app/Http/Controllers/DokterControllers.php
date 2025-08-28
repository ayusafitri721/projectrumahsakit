<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class DokterControllers extends Controller
{
    public function index()
    {
        // Tampilkan dokter tanpa relasi ruangan (karena tidak ada foreign key)
        $dktr = Dokter::latest()->paginate(5);
        return view('dktr.index', compact('dktr'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        // Ambil semua ruangan untuk dropdown
        $ruangans = Ruangan::orderBy('namaRuangan')->get();
        return view('dktr.create', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idDokter' => 'required|unique:dokters,idDokter',
            'namaDokter' => 'required',
            'tanggalLahir' => 'required|date|before:today',
            'spesialis' => 'required',
            'ruangan_id' => 'required|exists:ruangan,id', // Validasi untuk memastikan ruangan ada
            'jamPraktik' => 'required',
        ]);

        // Ambil data ruangan berdasarkan ruangan_id yang dipilih
        $ruangan = Ruangan::find($request->ruangan_id);
        
        // Simpan data dokter TANPA ruangan_id (karena kolom tidak ada)
        Dokter::create([
            'idDokter' => $request->idDokter,
            'namaDokter' => $request->namaDokter,
            'tanggalLahir' => $request->tanggalLahir,
            'spesialis' => $request->spesialis,
            // 'ruangan_id' => $request->ruangan_id, // HAPUS INI - kolom tidak ada
            'lokasiPraktik' => $ruangan->namaRuangan, // Simpan nama ruangan di sini
            'jamPraktik' => $request->jamPraktik,
        ]);

        return redirect()->route('dktr.index')->with('success', 
            "Data Dokter berhasil ditambahkan! Ruangan: {$ruangan->namaRuangan} - {$ruangan->lokasi}");
    }

    public function show(Dokter $dktr)
    {
        // Tanpa load relasi karena tidak ada foreign key
        return view('dktr.show', compact('dktr'));
    }

    public function edit(Dokter $dktr)
    {
        // Ambil semua ruangan untuk dropdown
        $ruangans = Ruangan::orderBy('namaRuangan')->get();
        
        // Cari ruangan yang sesuai dengan lokasiPraktik dokter saat ini
        $currentRuangan = Ruangan::where('namaRuangan', $dktr->lokasiPraktik)->first();
        
        return view('dktr.edit', compact('dktr', 'ruangans', 'currentRuangan'));
    }

    public function update(Request $request, Dokter $dktr)
    {
        $request->validate([
            'idDokter' => 'required|unique:dokters,idDokter,' . $dktr->id,
            'namaDokter' => 'required',
            'tanggalLahir' => 'required|date|before:today',
            'spesialis' => 'required',
            'ruangan_id' => 'required|exists:ruangan,id',
            'jamPraktik' => 'required',
        ]);

        // Ambil data ruangan baru
        $ruangan = Ruangan::find($request->ruangan_id);
        
        // Update data dokter TANPA ruangan_id
        $dktr->update([
            'idDokter' => $request->idDokter,
            'namaDokter' => $request->namaDokter,
            'tanggalLahir' => $request->tanggalLahir,
            'spesialis' => $request->spesialis,
            // 'ruangan_id' => $request->ruangan_id, // HAPUS INI - kolom tidak ada
            'lokasiPraktik' => $ruangan->namaRuangan, // Update nama ruangan
            'jamPraktik' => $request->jamPraktik,
        ]);

        return redirect()->route('dktr.index')->with('success', 
            "Data Dokter berhasil diupdate! Ruangan: {$ruangan->namaRuangan} - {$ruangan->lokasi}");
    }

    public function destroy(Dokter $dktr)
    {
        $dktr->delete();
        return redirect()->route('dktr.index')->with('success', 
            "Data Dokter berhasil dihapus! (Ruangan: {$dktr->lokasiPraktik})");
    }

    // AJAX method untuk ambil info ruangan berdasarkan ruangan_id
    public function getRuanganInfo($ruangan_id)
    {
        try {
            $ruangan = Ruangan::find($ruangan_id);
            
            if (!$ruangan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ruangan tidak ditemukan'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $ruangan->id,
                    'kodeRuangan' => $ruangan->kodeRuangan,
                    'namaRuangan' => $ruangan->namaRuangan,
                    'lokasi' => $ruangan->lokasi,
                    'dayaTampung' => $ruangan->dayaTampung,
                    'status' => $ruangan->dayaTampung > 0 ? 'tersedia' : 'penuh'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}