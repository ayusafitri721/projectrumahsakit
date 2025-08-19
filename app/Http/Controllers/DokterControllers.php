<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DokterControllers extends Controller
{
    public function index()
    {
        $dktr = Dokter::latest()->paginate(5);
        return view('dktr.index', compact('dktr'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('dktr.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'idDokter' => 'required',
            'namaDokter' => 'required',
            'tanggalLahir' => 'required',
            'spesialis' => 'required', // konsisten dengan model
            'lokasiPraktik' => 'required',
            'jamPraktik' => 'required',
        ]);

        Dokter::create($request->all());
        return redirect()->route('dktr.index')->with('success', 'Data Berhasil di Input'); // typo: succes -> success
    }

    public function show(Dokter $dktr)
    {
        return view('dktr.show', compact('dktr'));
    }

    public function edit(Dokter $dktr)
    {
        return view('dktr.edit', compact('dktr'));
    }

    public function update(Request $request, Dokter $dktr)
    {
        $request->validate([
            'idDokter' => 'required',
            'namaDokter' => 'required',
            'tanggalLahir' => 'required',
            'spesialis' => 'required', // ubah dari spesialisasi ke spesialis
            'lokasiPraktik' => 'required',
            'jamPraktik' => 'required',
        ]);

        $dktr->update($request->all());
        return redirect()->route('dktr.index')->with('success', 'Dokter Berhasil di Update'); // typo: succes -> success
    }

    public function destroy(Dokter $dktr)
    {
        $dktr->delete();
        return redirect()->route('dktr.index')->with('success', 'Dokter Berhasil di Hapus'); // typo: succes -> success
    }
}