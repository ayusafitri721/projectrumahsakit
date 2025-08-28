@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Detail Data Pasien</h4>
                        <div>
                            <a class="btn btn-warning btn-sm" href="{{ route('pasien.edit', $pasien->id) }}">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a class="btn btn-secondary btn-sm" href="{{ route('pasien.index') }}">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Informasi Pasien -->
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-user"></i> Informasi Pasien
                                </h5>

                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%" class="fw-bold">Nomor Rekam Medis</td>
                                        <td width="5%">:</td>
                                        <td>
                                            <span class="badge bg-primary fs-6">{{ $pasien->nomorRekamMedis }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Nama Pasien</td>
                                        <td>:</td>
                                        <td>{{ $pasien->namaPasien }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Tanggal Lahir</td>
                                        <td>:</td>
                                        <td>{{ $pasien->tanggalLahir->format('d F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Usia</td>
                                        <td>:</td>
                                        <td>
                                            <span class="badge bg-info">{{ $pasien->usiaPasien }} tahun</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Jenis Kelamin</td>
                                        <td>:</td>
                                        <td>
                                            @if ($pasien->jenisKelamin == 'Laki-laki')
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-mars"></i> {{ $pasien->jenisKelamin }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-venus"></i> {{ $pasien->jenisKelamin }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Alamat</td>
                                        <td>:</td>
                                        <td>{{ $pasien->alamatPasien }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Kota</td>
                                        <td>:</td>
                                        <td>{{ $pasien->kotaPasien }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Informasi Medis -->
                            <div class="col-md-6">
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-stethoscope"></i> Informasi Medis
                                </h5>

                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%" class="fw-bold">Penyakit</td>
                                        <td width="5%">:</td>
                                        <td>
                                            <span class="badge bg-warning text-dark">{{ $pasien->penyakitPasien }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Dokter</td>
                                        <td>:</td>
                                        <td>{{ $pasien->dokter->namaDokter ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold">Nomor Kamar</td>
                                        <td>:</td>
                                        <td>
                                            <span class="badge bg-secondary fs-6">{{ $pasien->nomorKamar }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Tanggal Masuk</td>
                                        <td>:</td>
                                        <td>
                                            <span class="badge bg-success">
                                                <i class="fas fa-calendar-plus"></i>
                                                {{ $pasien->tanggalMasuk->format('d F Y') }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Tanggal Keluar</td>
                                        <td>:</td>
                                        <td>
                                            @if ($pasien->tanggalKeluar)
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-calendar-minus"></i>
                                                    {{ $pasien->tanggalKeluar->format('d F Y') }}
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-clock"></i> Masih Dirawat
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Status</td>
                                        <td>:</td>
                                        <td>
                                            @if ($pasien->tanggalKeluar)
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-check-circle"></i> Sudah Keluar
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="fas fa-bed"></i> Sedang Dirawat
                                                </span>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Lama Rawat -->
                                    @if ($pasien->tanggalKeluar)
                                        <tr>
                                            <td class="fw-bold">Lama Rawat</td>
                                            <td>:</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $pasien->tanggalMasuk->diffInDays($pasien->tanggalKeluar) + 1 }}
                                                    hari
                                                </span>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td class="fw-bold">Sudah Dirawat</td>
                                            <td>:</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $pasien->tanggalMasuk->diffInDays(now()) + 1 }} hari
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="text-info mb-3">
                                    <i class="fas fa-info-circle"></i> Informasi Tambahan
                                </h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title">Data Dibuat</h6>
                                                <p class="card-text">
                                                    <i class="fas fa-calendar"></i>
                                                    {{ $pasien->created_at->format('d F Y, H:i') }} WIB
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title">Terakhir Diupdate</h6>
                                                <p class="card-text">
                                                    <i class="fas fa-edit"></i>
                                                    {{ $pasien->updated_at->format('d F Y, H:i') }} WIB
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <hr class="my-4">
                        <div class="text-center">
                            <a href="{{ route('pasien.edit', $pasien->id) }}" class="btn btn-warning me-2">
                                <i class="fas fa-edit"></i> Edit Data
                            </a>

                            <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal"
                                data-bs-target="#deleteModal">
                                <i class="fas fa-trash"></i> Hapus Data
                            </button>

                            <a href="{{ route('pasien.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list"></i> Daftar Pasien
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data pasien <strong>{{ $pasien->namaPasien }}</strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Data yang sudah dihapus tidak dapat dikembalikan!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('pasien.destroy', $pasien->id) }}" method="POST" style="display: inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Hapus!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .table-borderless td {
                padding: 0.5rem 0.75rem;
            }

            .card {
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
                border: 1px solid rgba(0, 0, 0, 0.125);
            }

            .badge {
                font-size: 0.875em;
            }

            .bg-light {
                background-color: #f8f9fa !important;
            }
        </style>
    @endpush
@endsection
