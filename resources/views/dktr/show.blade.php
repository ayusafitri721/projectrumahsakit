@extends('template')

@section('content')
<div class="row mt-5 mb-5">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h2>Show Dokter</h2>
        </div>
        <div class="float-right">
            <a class="btn btn-secondary" href="{{ route('dktr.index') }}"> Back</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>ID Dokter:</strong>
            {{ $dktr->idDokter }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Nama Dokter:</strong>
            {{ $dktr->namaDokter }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Tanggal Lahir:</strong>
            {{ $dktr->tanggalLahir ? \Carbon\Carbon::parse($dktr->tanggalLahir)->format('d-m-Y') : '-' }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Spesialis:</strong>
            {{ $dktr->spesialis ?? '-' }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Lokasi Praktik:</strong>
            {{ $dktr->lokasiPraktik ?? '-' }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Jam Praktik:</strong>
            {{ $dktr->jamPraktik ? \Carbon\Carbon::parse($dktr->jamPraktik)->format('H:i') : '-' }}
        </div>
    </div>
</div>
@endsection