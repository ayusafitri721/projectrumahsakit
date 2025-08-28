@extends('template')

@section('content')
<div class="row mt-5 mb-5">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h2>Tambah Data Pasien Baru</h2>
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

<form action="{{ route('pasien.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <strong>Nomor Rekam Medis:</strong>
                <input type="text" name="nomorRekamMedis" class="form-control" placeholder="Nomor Rekam Medis" value="{{ old('nomorRekamMedis') }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Nama Pasien:</strong>
                <input type="text" name="namaPasien" class="form-control" placeholder="Nama Pasien" value="{{ old('namaPasien') }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Tanggal Lahir:</strong>
                <input type="date" name="tanggalLahir" class="form-control" value="{{ old('tanggalLahir') }}" required id="tanggalLahir">
                <small class="text-muted">Usia: <span id="usiaPasien"></span></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Jenis Kelamin:</strong>
                <select name="jenisKelamin" class="form-control" required>
                    <option value="">- Pilih Jenis Kelamin -</option>
                    <option value="Laki-laki" {{ old('jenisKelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenisKelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Alamat Pasien:</strong>
                <textarea name="alamatPasien" class="form-control" placeholder="Alamat Lengkap" required>{{ old('alamatPasien') }}</textarea>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Kota Pasien:</strong>
                <input type="text" name="kotaPasien" class="form-control" placeholder="Kota" value="{{ old('kotaPasien') }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Penyakit Pasien:</strong>
                <select name="penyakitPasien" class="form-control" required id="penyakitPasien">
                    <option value="">- Pilih Penyakit -</option>
                    @foreach($penyakitList as $penyakit)
                        <option value="{{ $penyakit->spesialis }}" {{ old('penyakitPasien') == $penyakit->spesialis ? 'selected' : '' }}>
                            {{ $penyakit->spesialis }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- KOLOM RUANGAN OTOMATIS DARI DOKTER -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Ruangan (otomatis dari dokter):</strong>
                <input type="text" name="namaRuangan" class="form-control" id="namaRuangan" readonly style="background-color: #f8f9fa;">
                <small class="text-muted">Ruangan akan otomatis terisi setelah memilih dokter</small>
            </div>
        </div>

        <!-- HIDDEN INPUT UNTUK NOMOR KAMAR (SESUAI FIELD ASLI) -->
        <input type="hidden" name="nomorKamar" id="nomorKamar" value="{{ old('nomorKamar') }}">

        <div class="col-md-6">
            <div class="form-group">
                <strong>Dokter:</strong>
                <select name="idDokter" class="form-control" required id="idDokter">
                    <option value="">- Pilih Penyakit Dulu -</option>
                    @if($dokters->count() > 0)
                        @foreach($dokters as $dokter)
                            <option value="{{ $dokter->id }}" 
                                    data-lokasi="{{ $dokter->lokasiPraktik }}"
                                    {{ old('idDokter') == $dokter->id ? 'selected' : '' }}>
                                {{ $dokter->namaDokter }} - {{ $dokter->lokasiPraktik }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Tanggal Masuk:</strong>
                <input type="date" name="tanggalMasuk" class="form-control" value="{{ old('tanggalMasuk', date('Y-m-d')) }}" required>
            </div>
        </div>

        {{-- <div class="col-md-6">
            <div class="form-group">
                <strong>Tanggal Keluar:</strong>
                <input type="date" name="tanggalKeluar" class="form-control" value="{{ old('tanggalKeluar') }}">
                <small class="text-muted">Kosongkan jika pasien belum keluar</small>
            </div>
        </div> --}}

        <!-- AREA INFO RUANGAN DENGAN DAYA TAMPUNG -->
        <div class="col-md-12">
            <div id="ruanganInfo" class="alert alert-info" style="display: none;">
                <h6><i class="fas fa-building"></i> Informasi Ruangan:</h6>
                <div id="ruanganDetails"></div>
            </div>
        </div>

        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                <i class="fas fa-save"></i> Simpan Data Pasien
            </button>
            <a href="{{ route('pasien.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    
    // Fungsi untuk menghitung usia
    function calculateAge(birthDate) {
        const today = new Date();
        const birth = new Date(birthDate);
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        return age;
    }

    // Update usia saat tanggal lahir berubah
    $('#tanggalLahir').on('change', function() {
        const birthDate = $(this).val();
        if (birthDate) {
            const age = calculateAge(birthDate);
            $('#usiaPasien').text(age + ' tahun');
        }
    });

    // Hitung usia awal
    if ($('#tanggalLahir').val()) {
        $('#tanggalLahir').trigger('change');
    }

    // Load dokter berdasarkan penyakit
    $('#penyakitPasien').change(function() {
        var penyakit = $(this).val();
        
        // Reset dokter dan ruangan
        $('#idDokter').empty().append('<option value="">- Pilih Penyakit Dulu -</option>');
        $('#namaRuangan').val('');
        $('#nomorKamar').val('');
        $('#ruanganInfo').fadeOut();
        $('#submitBtn').prop('disabled', false).removeClass('btn-secondary btn-danger').addClass('btn-primary');
        
        if (penyakit) {
            // Simpan data form
            sessionStorage.setItem('formData', JSON.stringify({
                nomorRekamMedis: $('input[name="nomorRekamMedis"]').val(),
                namaPasien: $('input[name="namaPasien"]').val(),
                tanggalLahir: $('input[name="tanggalLahir"]').val(),
                jenisKelamin: $('select[name="jenisKelamin"]').val(),
                alamatPasien: $('textarea[name="alamatPasien"]').val(),
                kotaPasien: $('input[name="kotaPasien"]').val(),
                penyakitPasien: penyakit,
                tanggalMasuk: $('input[name="tanggalMasuk"]').val(),
                tanggalKeluar: $('input[name="tanggalKeluar"]').val()
            }));
            
            // Redirect untuk load dokter
            window.location.href = '{{ url("/pasien/create") }}?penyakit=' + encodeURIComponent(penyakit);
        }
    });

    // FUNGSI UTAMA: Auto set ruangan berdasarkan dokter yang dipilih dengan CEK DAYA TAMPUNG
    $('#idDokter').change(function() {
        var selectedOption = $(this).find('option:selected');
        var lokasiPraktik = selectedOption.data('lokasi');
        var namaDokter = selectedOption.text();
        var dokterId = $(this).val();
        
        console.log('Dokter dipilih:', dokterId, 'Lokasi/Ruangan:', lokasiPraktik);
        
        if (lokasiPraktik && dokterId) {
            // Cek daya tampung ruangan dulu via AJAX
            checkKapasitasRuangan(lokasiPraktik, function(kapasitasData) {
                console.log('Data kapasitas:', kapasitasData);
                
                if (kapasitasData.available > 0) {
                    // RUANGAN TERSEDIA
                    $('#namaRuangan').val(lokasiPraktik);
                    $('#nomorKamar').val(lokasiPraktik);
                    
                    updateRuanganInfo({
                        nama: lokasiPraktik,
                        dokter: namaDokter.split(' - ')[0],
                        dayaTampung: kapasitasData.total,
                        terisi: kapasitasData.occupied,
                        tersedia: kapasitasData.available,
                        status: 'available'
                    });
                    
                    // Enable submit button
                    $('#submitBtn').prop('disabled', false)
                                  .removeClass('btn-secondary btn-danger')
                                  .addClass('btn-primary')
                                  .html('<i class="fas fa-save"></i> Simpan Data Pasien');
                    
                } else {
                    // RUANGAN PENUH
                    $('#namaRuangan').val(lokasiPraktik + ' (PENUH)');
                    $('#nomorKamar').val(''); // Kosongkan untuk validasi
                    
                    updateRuanganInfo({
                        nama: lokasiPraktik,
                        dokter: namaDokter.split(' - ')[0],
                        dayaTampung: kapasitasData.total,
                        terisi: kapasitasData.occupied,
                        tersedia: kapasitasData.available,
                        status: 'full'
                    });
                    
                    // Disable submit button
                    $('#submitBtn').prop('disabled', true)
                                  .removeClass('btn-primary')
                                  .addClass('btn-danger')
                                  .html('<i class="fas fa-times"></i> RUANGAN PENUH - Pilih Dokter Lain');
                    
                    // Alert warning
                    setTimeout(function() {
                        alert('RUANGAN PENUH!\n\nRuangan "' + lokasiPraktik + '" sudah mencapai kapasitas maksimal (' + kapasitasData.total + ' pasien).\n\nSilakan pilih dokter lain dengan ruangan yang masih tersedia.');
                    }, 500);
                }
            });
            
        } else {
            // Reset jika tidak ada dokter dipilih
            $('#namaRuangan').val('');
            $('#nomorKamar').val('');
            $('#ruanganInfo').hide();
            $('#submitBtn').prop('disabled', false).removeClass('btn-secondary btn-danger').addClass('btn-primary');
        }
    });

    // Fungsi untuk cek kapasitas ruangan via AJAX
    function checkKapasitasRuangan(namaRuangan, callback) {
        console.log('Checking kapasitas untuk:', namaRuangan);
        
        $.ajax({
            url: '{{ url("/get-kapasitas-ruangan") }}',
            type: 'GET',
            data: { ruangan: namaRuangan },
            dataType: 'json',
            success: function(response) {
                console.log('Response kapasitas dari server:', response);
                
                if (response.success && response.dayaTampung !== undefined) {
                    // Gunakan data ASLI dari database
                    var totalKapasitas = parseInt(response.dayaTampung);
                    var terisiSaatIni = parseInt(response.terisi) || 0;
                    var sisaKapasitas = parseInt(response.tersedia) || 0;
                    
                    console.log('Data kapasitas:', {
                        total: totalKapasitas,
                        terisi: terisiSaatIni, 
                        sisa: sisaKapasitas
                    });
                    
                    callback({
                        total: totalKapasitas,
                        occupied: terisiSaatIni,
                        available: Math.max(0, sisaKapasitas)
                    });
                } else {
                    console.log('Data ruangan tidak ditemukan, menggunakan fallback');
                    // Jika ruangan tidak ditemukan di database
                    callback({
                        total: 0,
                        occupied: 0,
                        available: 0
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', status, error);
                console.log('Response Text:', xhr.responseText);
                
                // ERROR: Tidak bisa akses server, BLOKIR untuk safety
                callback({
                    total: 0,
                    occupied: 999,
                    available: 0
                });
            }
        });
    }

    // Fungsi untuk update info ruangan dengan status kapasitas
    function updateRuanganInfo(ruangan) {
        var statusBadge = '';
        var alertClass = 'alert-success';
        var statusIcon = 'fas fa-check-circle';
        var statusText = 'Tersedia';
        
        if (ruangan.status === 'full') {
            statusBadge = '<span class="badge badge-danger badge-lg"><i class="fas fa-times"></i> PENUH</span>';
            alertClass = 'alert-danger';
            statusIcon = 'fas fa-exclamation-triangle';
            statusText = 'Penuh';
        } else {
            var percentage = Math.round((ruangan.terisi / ruangan.dayaTampung) * 100);
            
            if (percentage >= 80) {
                statusBadge = '<span class="badge badge-warning badge-lg"><i class="fas fa-exclamation-triangle"></i> HAMPIR PENUH</span>';
                alertClass = 'alert-warning';
                statusIcon = 'fas fa-exclamation-triangle';
                statusText = 'Hampir Penuh';
            } else if (percentage >= 50) {
                statusBadge = '<span class="badge badge-info badge-lg"><i class="fas fa-info-circle"></i> SETENGAH PENUH</span>';
                alertClass = 'alert-info';
                statusIcon = 'fas fa-info-circle';
                statusText = 'Setengah Penuh';
            } else {
                statusBadge = '<span class="badge badge-success badge-lg"><i class="fas fa-check-circle"></i> TERSEDIA</span>';
                alertClass = 'alert-success';
                statusIcon = 'fas fa-check-circle';
                statusText = 'Tersedia';
            }
        }
        
        $('#ruanganDetails').html(`
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-2">
                        <strong><i class="fas fa-building"></i> Nama Ruangan:</strong> ${ruangan.nama}<br>
                        <strong><i class="fas fa-user-md"></i> Dokter:</strong> ${ruangan.dokter}<br>
                        <strong><i class="fas fa-bed"></i> Daya Tampung:</strong> ${ruangan.dayaTampung} pasien<br>
                        <strong><i class="fas fa-users"></i> Saat ini terisi:</strong> ${ruangan.terisi} pasien<br>
                        <strong><i class="fas fa-plus-circle"></i> Sisa kapasitas:</strong> 
                        <span class="${ruangan.tersedia > 0 ? 'text-success' : 'text-danger'}" style="font-weight: bold;">
                            ${ruangan.tersedia} tempat
                        </span>
                    </div>
                    <small class="text-muted">
                        <i class="${statusIcon}"></i> Status: ${statusText} | 
                        Sumber: Otomatis dari Dokter
                    </small>
                </div>
                <div class="col-md-4 text-right">
                    ${statusBadge}
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar ${ruangan.status === 'full' ? 'bg-danger' : (ruangan.terisi / ruangan.dayaTampung >= 0.8 ? 'bg-warning' : 'bg-success')}" 
                             style="width: ${Math.min(100, (ruangan.terisi / ruangan.dayaTampung) * 100)}%"></div>
                    </div>
                    <small class="text-muted">${Math.round((ruangan.terisi / ruangan.dayaTampung) * 100)}% terisi</small>
                </div>
            </div>
        `);
        
        $('#ruanganInfo').removeClass('alert-warning alert-danger alert-success alert-info')
                        .addClass(alertClass).fadeIn();
        
        console.log('Ruangan info updated:', ruangan.nama, '-', statusText);
    }

    // Restore form data
    var savedData = sessionStorage.getItem('formData');
    if (savedData) {
        var data = JSON.parse(savedData);
        
        $('input[name="nomorRekamMedis"]').val(data.nomorRekamMedis);
        $('input[name="namaPasien"]').val(data.namaPasien);
        $('input[name="tanggalLahir"]').val(data.tanggalLahir);
        $('select[name="jenisKelamin"]').val(data.jenisKelamin);
        $('textarea[name="alamatPasien"]').val(data.alamatPasien);
        $('input[name="kotaPasien"]').val(data.kotaPasien);
        $('select[name="penyakitPasien"]').val(data.penyakitPasien);
        $('input[name="tanggalMasuk"]').val(data.tanggalMasuk);
        $('input[name="tanggalKeluar"]').val(data.tanggalKeluar);
        
        if (data.tanggalLahir) {
            $('#tanggalLahir').trigger('change');
        }
        
        sessionStorage.removeItem('formData');
    }

    // Trigger auto select jika dokter sudah terpilih saat load
    if ($('#idDokter').val()) {
        $('#idDokter').trigger('change');
    }

    // VALIDASI KETAT SEBELUM SUBMIT
    $('form').on('submit', function(e) {
        var nomorKamar = $('#nomorKamar').val();
        var namaRuangan = $('#namaRuangan').val();
        var penyakit = $('#penyakitPasien').val();
        var dokter = $('#idDokter').val();
        var submitBtn = $('#submitBtn');
        
        console.log('Validasi submit:', {
            penyakit: penyakit,
            dokter: dokter,
            namaRuangan: namaRuangan,
            nomorKamar: nomorKamar,
            buttonDisabled: submitBtn.prop('disabled')
        });
        
        // Validasi dasar
        if (!penyakit) {
            e.preventDefault();
            alert('Silakan pilih penyakit terlebih dahulu!');
            return false;
        }
        
        if (!dokter) {
            e.preventDefault();
            alert('Silakan pilih dokter terlebih dahulu!');
            return false;
        }
        
        // Validasi ruangan penuh
        if (!nomorKamar || nomorKamar.trim() === '') {
            e.preventDefault();
            alert('RUANGAN PENUH!\n\nRuangan tidak tersedia atau sudah penuh. Silakan:\n• Pilih dokter lain dengan ruangan berbeda\n• Tunggu ada pasien yang keluar');
            return false;
        }
        
        // Cek button disabled (double check)
        if (submitBtn.prop('disabled')) {
            e.preventDefault();
            alert('TIDAK DAPAT MENYIMPAN!\n\nRuangan sudah penuh. Silakan pilih dokter lain.');
            return false;
        }
        
        // Cek jika ruangan mengandung kata "PENUH"
        if (namaRuangan.includes('PENUH')) {
            e.preventDefault();
            alert('RUANGAN PENUH!\n\nRuangan yang dipilih sudah mencapai kapasitas maksimal.\nSilakan pilih dokter lain.');
            return false;
        }
        
        // Konfirmasi final
        var konfirmasi = confirm(`Konfirmasi Data Pasien\n\nApakah data sudah benar?\n\nPenyakit: ${penyakit}\nDokter: ${$('#idDokter option:selected').text()}\nRuangan: ${namaRuangan || nomorKamar}\n\nData akan disimpan dan ruangan akan terisi!`);
        if (!konfirmasi) {
            e.preventDefault();
            return false;
        }
        
        console.log('Form akan disubmit dengan nomorKamar:', nomorKamar);
        return true;
    });
    
    // Auto-refresh kapasitas setiap 30 detik untuk update real-time
    setInterval(function() {
        if ($('#idDokter').val() && $('#namaRuangan').val()) {
            console.log('Auto-refresh kapasitas ruangan...');
            $('#idDokter').trigger('change');
        }
    }, 30000); // 30 detik
});
</script>

@endsection