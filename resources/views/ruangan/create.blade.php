<!-- resources/views/ruangan/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Tambah Ruangan Baru</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('ruangan.index') }}"> Back</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Ada yang salah dengan input anda.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ruangan.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Kode Ruangan:</strong>
                    <input type="text" name="kodeRuangan" class="form-control" placeholder="Kode Ruangan" value="{{ old('kodeRuangan') }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Nama Ruangan:</strong>
                    <input type="text" name="namaRuangan" class="form-control" placeholder="Nama Ruangan" value="{{ old('namaRuangan') }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Daya Tampung:</strong>
                    <input type="number" name="dayaTampung" class="form-control" placeholder="Daya Tampung" value="{{ old('dayaTampung') }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Lokasi:</strong>
                    <input type="text" name="lokasi" class="form-control" placeholder="Lokasi" value="{{ old('lokasi') }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <br>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>
@endsection