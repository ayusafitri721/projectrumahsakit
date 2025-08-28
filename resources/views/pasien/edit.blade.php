<style>
/* Style untuk field yang readonly */
.bg-light {
    background-color: #f8f9fa !important;
    cursor: not-allowed;
}

/* Style untuk field yang bisa diedit */
.border-primary {
    border-color: #007bff !important;
    border-width: 2px !important;
}

.border-primary:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Highlight untuk section yang bisa diedit */
.form-group:has(.border-primary) strong {
    color: #007bff !important;
    font-weight: bold;
}

/* Badge styling */
.badge-lg {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}
</style>
    @extends('template')

@section('content')
<div class="row mt-5 mb-5">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h2>Edit Data Pasien</h2>
        </div>
        <div class="float-right">
            <a class="btn btn-secondary" href="{{ route('pasien.index') }}"> Kembali</a>
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> Input gagal.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        <strong>Error!</strong> {{ session('error') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        <strong>Sukses!</strong> {{ session('success') }}
    </div>
@endif

<form action="{{ route('pasien.update', $pasien->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- DATA YANG TIDAK BISA DIEDIT (READ ONLY) -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Nomor Rekam Medis:</strong>
                <input type="text" class="form-control bg-light" value="{{ $pasien->nomorRekamMedis }}" readonly>
                <input type="hidden" name="nomorRekamMedis" value="{{ $pasien->nomorRekamMedis }}">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Nama Pasien: <span class="text-danger">*</span></strong>
                <input type="text" name="namaPasien" class="form-control border-primary" value="{{ old('namaPasien', $pasien->namaPasien) }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Tanggal Lahir:</strong>
                <input type="date" class="form-control bg-light" value="{{ $pasien->tanggalLahir ? date('Y-m-d', strtotime($pasien->tanggalLahir)) : '' }}" readonly id="tanggalLahir">
                <input type="hidden" name="tanggalLahir" value="{{ $pasien->tanggalLahir }}">
                <small class="text-muted">Usia: <span id="usiaPasien">{{ $pasien->usiaPasien ?? 0 }}</span> tahun</small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Jenis Kelamin:</strong>
                <input type="text" class="form-control bg-light" value="{{ $pasien->jenisKelamin }}" readonly>
                <input type="hidden" name="jenisKelamin" value="{{ $pasien->jenisKelamin }}">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Alamat Pasien:</strong>
                <textarea class="form-control bg-light" readonly>{{ $pasien->alamatPasien }}</textarea>
                <input type="hidden" name="alamatPasien" value="{{ $pasien->alamatPasien }}">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Kota Pasien:</strong>
                <input type="text" class="form-control bg-light" value="{{ $pasien->kotaPasien }}" readonly>
                <input type="hidden" name="kotaPasien" value="{{ $pasien->kotaPasien }}">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Penyakit Pasien:</strong>
                <input type="text" class="form-control bg-light" value="{{ $pasien->penyakitPasien }}" readonly>
                <input type="hidden" name="penyakitPasien" value="{{ $pasien->penyakitPasien }}">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Ruangan (otomatis dari dokter):</strong>
                <input type="text" class="form-control bg-light" value="{{ $pasien->nomorKamar }}" readonly id="namaRuangan">
                <small class="text-muted">Ruangan sudah otomatis terisi dari lokasi praktek dokter</small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Dokter:</strong>
                <input type="text" class="form-control bg-light" value="{{ $pasien->dokter->namaDokter ?? 'Dokter tidak ditemukan' }}" readonly>
                <input type="hidden" name="idDokter" value="{{ $pasien->idDokter }}">
            </div>
        </div>

        <!-- HIDDEN INPUT UNTUK KAMAR OTOMATIS -->
        <input type="hidden" name="nomorKamar" id="nomorKamar" value="{{ old('nomorKamar', $pasien->nomorKamar) }}">

        <!-- AREA INFO KAMAR YANG DIPILIH OTOMATIS -->
        <div class="col-md-12">
            <div id="kamarInfo" class="alert alert-info">
                <h6><i class="fas fa-bed"></i> Kamar Otomatis Terpilih:</h6>
                <div id="kamarDetails">
                    <div class="row">
                        <div class="col-md-8">
                            <strong>Dokter:</strong> 
                            @if(isset($pasien->dokter) && $pasien->dokter)
                                {{ $pasien->dokter->namaDokter }}
                            @else
                                Dokter ID: {{ $pasien->idDokter }} (Perlu update data dokter)
                            @endif
                            <br>
                            <strong>Kamar:</strong> {{ $pasien->nomorKamar }}<br>
                            <small class="text-muted">Kamar akan otomatis dipilih berdasarkan lokasi praktek dokter</small>
                        </div>
                        <div class="col-md-4 text-right">
                            <span class="badge badge-success badge-lg">
                                <i class="fas fa-check"></i> Auto Selected
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DATA YANG BISA DIEDIT - TANGGAL -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Tanggal Masuk: <span class="text-danger">*</span></strong>
                <input type="date" name="tanggalMasuk" class="form-control border-primary" 
                       value="{{ old('tanggalMasuk', $pasien->tanggalMasuk ? date('Y-m-d', strtotime($pasien->tanggalMasuk)) : date('Y-m-d')) }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Tanggal Keluar:</strong>
                <input type="date" name="tanggalKeluar" class="form-control border-primary" 
                       value="{{ old('tanggalKeluar', $pasien->tanggalKeluar ? date('Y-m-d', strtotime($pasien->tanggalKeluar)) : '') }}">
                <small class="text-muted">Kosongkan jika pasien belum keluar</small>
            </div>
        </div>

        <!-- INFO BOX -->
        <div class="col-md-12">
            <div class="alert alert-warning">
                <h6>Informasi Edit:</h6>
                <ul class="mb-0">
                    <li><strong>Yang bisa diedit (Border Biru):</strong> Nama Pasien, Tanggal Masuk, Tanggal Keluar</li>
                    <li><strong>Yang tidak bisa diedit (Background Abu-abu):</strong> Nomor Rekam Medis, Tanggal Lahir, Jenis Kelamin, Alamat, Kota, Pemakai, Penyakit, Dokter</li>
                    <li><strong>Otomatis:</strong> Kamar sudah ditetapkan sesuai dokter yang ada</li>
                </ul>
            </div>
        </div>

        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-success btn-lg px-4">
                <i class="fas fa-save"></i> Update Data Pasien
            </button>
            <a href="{{ route('pasien.index') }}" class="btn btn-secondary btn-lg px-4">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    
    // Fungsi untuk menghitung usia (sama seperti di create.blade.php)
    function calculateAge(birthDate) {
        if (!birthDate) return 0;
        
        const today = new Date();
        const birth = new Date(birthDate);
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        return age;
    }

    // Hitung dan update usia saat load
    function updateAge() {
        const birthDate = $('#tanggalLahir').val();
        if (birthDate) {
            const age = calculateAge(birthDate);
            $('#usiaPasien').text(age);
            console.log('Usia dihitung:', age, 'dari tanggal:', birthDate);
        } else {
            console.log('Tanggal lahir kosong');
        }
    }

    // Update usia saat load halaman
    updateAge();
    
    // Validasi tanggal keluar harus setelah atau sama dengan tanggal masuk
    $('input[name="tanggalKeluar"]').on('change', function() {
        var tanggalMasuk = new Date($('input[name="tanggalMasuk"]').val());
        var tanggalKeluar = new Date($(this).val());
        
        if ($(this).val() && tanggalKeluar < tanggalMasuk) {
            alert('Tanggal keluar tidak boleh lebih awal dari tanggal masuk!');
            $(this).val('');
        }
    });

    // Validasi sebelum submit
    $('form').on('submit', function(e) {
        var namaPasien = $('input[name="namaPasien"]').val().trim();
        var tanggalMasuk = $('input[name="tanggalMasuk"]').val();
        var tanggalKeluar = $('input[name="tanggalKeluar"]').val();
        
        if (!namaPasien) {
            e.preventDefault();
            alert('Nama pasien harus diisi!');
            $('input[name="namaPasien"]').focus();
            return false;
        }
        
        if (!tanggalMasuk) {
            e.preventDefault();
            alert('Tanggal masuk harus diisi!');
            $('input[name="tanggalMasuk"]').focus();
            return false;
        }
        
        if (tanggalKeluar && new Date(tanggalKeluar) < new Date(tanggalMasuk)) {
            e.preventDefault();
            alert('Tanggal keluar tidak boleh lebih awal dari tanggal masuk!');
            $('input[name="tanggalKeluar"]').focus();
            return false;
        }
        
        var konfirmasi = confirm('Apakah Anda yakin ingin mengupdate data pasien ini?');
        if (!konfirmasi) {
            e.preventDefault();
            return false;
        }
    });

    // Auto focus ke field yang bisa diedit pertama
    $('input[name="namaPasien"]').focus();
});
</script>

<style>
/* Style untuk field yang readonly */
.bg-light {
    background-color: #f8f9fa !important;
    cursor: not-allowed;
    opacity: 0.8;
}

/* Style untuk field yang bisa diedit - DIPERBAIKI */
.border-primary {
    border-color: #007bff !important;
    border-width: 2px !important;
    background-color: #ffffff !important; /* Pastikan background putih */
}

.border-primary:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    border-color: #007bff !important;
    background-color: #ffffff !important;
}

/* Pastikan select dropdown bisa diklik */
select.border-primary {
    cursor: pointer !important;
    pointer-events: auto !important;
}

select.border-primary:disabled {
    cursor: not-allowed !important;
    background-color: #e9ecef !important;
    opacity: 0.65;
}

/* Highlight untuk section yang bisa diedit */
.form-group:has(.border-primary) strong {
    color: #007bff !important;
    font-weight: bold;
}

/* Small text untuk field yang bisa diedit */
.text-primary {
    color: #007bff !important;
    font-weight: 500;
}

/* Validation styling */
.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

/* Badge styling */
.badge-lg {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

/* Button spacing */
.btn.px-4 {
    padding-left: 1.5rem !important;
    padding-right: 1.5rem !important;
}

/* Card and alert improvements */
.alert {
    border-radius: 0.5rem;
}

.alert h6 {
    margin-bottom: 0.5rem;
    font-weight: bold;
}

/* Form improvements */
.form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Responsive improvements */
@media (max-width: 768px) {
    .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .btn-lg {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}

/* Visual indicator untuk field yang bisa diedit */
.border-primary + small.text-primary {
    font-style: italic;
    display: block;
    margin-top: 0.25rem;
}

/* Highlight perbedaan editable vs readonly */
.form-group:has(.bg-light) {
    position: relative;
}

.form-group:has(.bg-light)::before {
    content: "";
    position: absolute;
    right: 10px;
    top: 25px;
    color: #6c757d;
    font-size: 12px;
}

.form-group:has(.border-primary)::before {
    content: "";
    position: absolute;
    right: 10px;
    top: 25px;
    font-size: 12px;
}
</style>

@endsection