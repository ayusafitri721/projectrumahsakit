@extends('template')

@section('content')
<div class="row mt-5 mb-5">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h2>Data Pasien</h2>
        </div>
        <div class="float-right">
            <a class="btn btn-success" href="{{ route('pasien.create') }}"> Tambah Pasien Baru</a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-danger">
        <p>{{ $message }}</p>
    </div>
@endif

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <tr class="table-dark">
            <th>No</th>
            <th>No. Rekam Medis</th>
            <th>Nama Pasien</th>
            <th>Usia</th>
            <th>Jenis Kelamin</th>
            <th>Kota</th>
            <th>Penyakit</th>
            <th>Dokter</th>
            <th>Ruangan</th>
            <th>Tanggal Masuk</th>
            <th>Status</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($pasiens as $pasien)
        <tr>
            <td>{{ ++$i }}</td>
            <td>
                <span class="badge badge-primary">{{ $pasien->nomorRekamMedis }}</span>
            </td>
            <td>{{ $pasien->namaPasien }}</td>
            <td>
                @if ($pasien->usiaPasien >= 0)
                    {{ $pasien->usiaPasien }} tahun
                @else
                    <span class="badge badge-danger">Usia Tidak Valid</span>
                @endif
            </td>
            <td>
                @if($pasien->jenisKelamin == 'Laki-laki')
                    <span class="badge badge-info">
                        <i class="fas fa-mars"></i> {{ $pasien->jenisKelamin }}
                    </span>
                @else
                    <span class="badge badge-danger">
                        <i class="fas fa-venus"></i> {{ $pasien->jenisKelamin }}
                    </span>
                @endif
            </td>
            <td>{{ $pasien->kotaPasien }}</td>
            <td>
                <span class="badge badge-warning">{{ $pasien->penyakitPasien }}</span>
            </td>
            <td>
                @if($pasien->dokter)
                    <span class="badge badge-primary">
                        <i class="fas fa-user-md"></i> {{ $pasien->dokter->namaDokter }}
                    </span>
                    <br>
                    <small class="text-muted">{{ $pasien->dokter->spesialis }}</small>
                @else
                    <span class="badge badge-secondary">
                        <i class="fas fa-question-circle"></i> Dokter Tidak Ditemukan
                    </span>
                @endif
            </td>
            <td>
                <span class="badge badge-secondary">{{ $pasien->nomorKamar }}</span>
            </td>
            <td>
                <span class="badge badge-light">
                    <i class="fas fa-calendar-plus"></i> {{ $pasien->tanggalMasuk->format('d-m-Y') }}
                </span>
            </td>
            <td>
                @if($pasien->tanggalKeluar)
                    <span class="badge badge-success">
                        <i class="fas fa-check-circle"></i> Keluar
                    </span><br>
                    <small class="text-muted">
                        <i class="fas fa-calendar-minus"></i> {{ $pasien->tanggalKeluar->format('d-m-Y') }}
                    </small>
                @else
                    <span class="badge badge-warning">
                        <i class="fas fa-bed"></i> Dirawat
                    </span>
                @endif
            </td>
            <td>
                <div class="btn-group" role="group">
                    <a class="btn btn-info btn-sm" href="{{ route('pasien.show', $pasien->id) }}" title="Lihat Detail">
                        <i class="fas fa-eye"></i> Show
                    </a>
                    <a class="btn btn-warning btn-sm" href="{{ route('pasien.edit', $pasien->id) }}" title="Edit Data">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    
                    <form action="{{ route('pasien.destroy', $pasien->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data pasien {{ $pasien->namaPasien }}?')" title="Hapus Data">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </table>
</div>

<div class="d-flex justify-content-center">
    {!! $pasiens->links() !!}
</div>

@push('styles')
<style>
.table th {
    vertical-align: middle;
    text-align: center;
    font-size: 0.9em;
}

.table td {
    vertical-align: middle;
    font-size: 0.85em;
}

.badge {
    font-size: 0.8em;
    padding: 0.25em 0.5em;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.75em;
    }
    
    .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.7rem;
    }
}
</style>
@endpush

@endsection