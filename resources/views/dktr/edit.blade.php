@extends('template')

@section('content')

<!-- Navbar dengan Profile Photo -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dktr.index') }}">
            <i class="fas fa-hospital"></i> SIRS Dashboard
        </a>
        
        <div class="navbar-nav ms-auto">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                    @if(Auth::user()->profile_photo && \Storage::disk('public')->exists(Auth::user()->profile_photo))
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                             class="rounded-circle me-2" width="30" height="30" 
                             style="object-fit: cover;">
                    @endif
                    {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu">
                    <li><span class="dropdown-item-text"><small>{{ Auth::user()->email }}</small></span></li>
                    <li><span class="dropdown-item-text"><small>Role: {{ Auth::user()->role }}</small></span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="row mt-5 mb-5">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h2>Edit Dokter</h2>
        </div>
        <div class="float-right">
            <a class="btn btn-secondary" href="{{ route('dktr.index') }}"> Back</a>
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('dktr.update',$dktr->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>ID Dokter:</strong>
                <input type="text" name="idDokter" class="form-control" placeholder="ID Dokter" value="{{ old('idDokter', $dktr->idDokter) }}">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Nama Dokter:</strong>
                <input type="text" name="namaDokter" class="form-control" placeholder="NAMA DOKTER" value="{{ old('namaDokter', $dktr->namaDokter) }}">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Tanggal Lahir:</strong>
                <input type="date" name="tanggalLahir" class="form-control" 
                       value="{{ old('tanggalLahir', $dktr->tanggalLahir) }}">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Spesialis:</strong>
                <select class="form-control" name="spesialis">
                    <option value="">- Pilih Spesialis -</option>
                    <option value="Poli Umum" {{ old('spesialis', $dktr->spesialis) == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                    <option value="Poli Anak" {{ old('spesialis', $dktr->spesialis) == 'Poli Anak' ? 'selected' : '' }}>Poli Anak</option>
                    <option value="Poli Gigi" {{ old('spesialis', $dktr->spesialis) == 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                    <option value="Poli Mata" {{ old('spesialis', $dktr->spesialis) == 'Poli Mata' ? 'selected' : '' }}>Poli Mata</option>
                    <option value="Poli Kulit" {{ old('spesialis', $dktr->spesialis) == 'Poli Kulit' ? 'selected' : '' }}>Poli Kulit</option>
                    <option value="Poli Penyakit Dalam" {{ old('spesialis', $dktr->spesialis) == 'Poli Penyakit Dalam' ? 'selected' : '' }}>Poli Penyakit Dalam</option>
                    <option value="Poli Konseling" {{ old('spesialis', $dktr->spesialis) == 'Poli Konseling' ? 'selected' : '' }}>Poli Konseling</option>
                    <option value="Poli Saraf" {{ old('spesialis', $dktr->spesialis) == 'Poli Saraf' ? 'selected' : '' }}>Poli Saraf</option>
                    <option value="Poli THT" {{ old('spesialis', $dktr->spesialis) == 'Poli THT' ? 'selected' : '' }}>Poli THT</option>
                    <option value="Poli Bedah" {{ old('spesialis', $dktr->spesialis) == 'Poli Bedah' ? 'selected' : '' }}>Poli Bedah</option>
                    <option value="Poli Paru" {{ old('spesialis', $dktr->spesialis) == 'Poli Paru' ? 'selected' : '' }}>Poli Paru</option>
                    <option value="Poli Jantung" {{ old('spesialis', $dktr->spesialis) == 'Poli Jantung' ? 'selected' : '' }}>Poli Jantung</option>
                    <option value="Poli Gizi Klinik" {{ old('spesialis', $dktr->spesialis) == 'Poli Gizi Klinik' ? 'selected' : '' }}>Poli Gizi Klinik</option>
                </select>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Ruangan:</strong>
                <select class="form-control" name="ruangan_id">
                    <option value="">- Pilih Ruangan -</option>
                    @foreach($ruangans as $ruangan)
                        @php
                            // Cari ruangan yang sesuai dengan lokasiPraktik dokter saat ini
                            $isSelected = ($ruangan->namaRuangan == $dktr->lokasiPraktik) || (old('ruangan_id') == $ruangan->id);
                        @endphp
                        <option value="{{ $ruangan->id }}" {{ $isSelected ? 'selected' : '' }}>
                            {{ $ruangan->namaRuangan }} ({{ $ruangan->kodeRuangan }}) - {{ $ruangan->lokasi }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Jam Praktik:</strong>
                <input type="time" name="jamPraktik" class="form-control" value="{{ old('jamPraktik', $dktr->jamPraktik) }}">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>
@endsection