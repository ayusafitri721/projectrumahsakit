@extends('template')

@section('content')
    <div class="row mt-5 mb-5">
        <div class="col-lg-12 margin-tb">
            <div class="float-left">
                <h2>Tambah Data Dokter Baru</h2>
            </div>
            <div class="float-right">
                <a class="btn btn-secondary" href="{{ route('dktr.index') }}"> Kembali</a>
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

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Sukses!</strong> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('dktr.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>ID Dokter: <span class="text-danger">*</span></strong>
                    <input type="text" name="idDokter" class="form-control" placeholder="ID DOKTER (contoh: DK-ASW)" value="{{ old('idDokter') }}" required>
                    <small class="text-muted">ID unik untuk dokter</small>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <strong>Nama Dokter: <span class="text-danger">*</span></strong>
                    <input type="text" name="namaDokter" class="form-control" placeholder="NAMA DOKTER" value="{{ old('namaDokter') }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <strong>Tanggal Lahir: <span class="text-danger">*</span></strong>
                    <input type="date" class="form-control" name="tanggalLahir" value="{{ old('tanggalLahir') }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <strong>Spesialis: <span class="text-danger">*</span></strong>
                    <select class="form-control" name="spesialis" required>
                        <option value="">- Pilih Spesialis -</option>
                        <option value="Poli Umum" {{ old('spesialis') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                        <option value="Poli Anak" {{ old('spesialis') == 'Poli Anak' ? 'selected' : '' }}>Poli Anak</option>
                        <option value="Poli Gigi" {{ old('spesialis') == 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                        <option value="Poli Mata" {{ old('spesialis') == 'Poli Mata' ? 'selected' : '' }}>Poli Mata</option>
                        <option value="Poli Kulit" {{ old('spesialis') == 'Poli Kulit' ? 'selected' : '' }}>Poli Kulit</option>
                        <option value="Poli Penyakit Dalam" {{ old('spesialis') == 'Poli Penyakit Dalam' ? 'selected' : '' }}>Poli Penyakit Dalam</option>
                        <option value="Poli Konseling" {{ old('spesialis') == 'Poli Konseling' ? 'selected' : '' }}>Poli Konseling</option>
                        <option value="Poli Saraf" {{ old('spesialis') == 'Poli Saraf' ? 'selected' : '' }}>Poli Saraf</option>
                        <option value="Poli THT" {{ old('spesialis') == 'Poli THT' ? 'selected' : '' }}>Poli THT</option>
                        <option value="Poli Bedah" {{ old('spesialis') == 'Poli Bedah' ? 'selected' : '' }}>Poli Bedah</option>
                        <option value="Poli Paru" {{ old('spesialis') == 'Poli Paru' ? 'selected' : '' }}>Poli Paru</option>
                        <option value="Poli Jantung" {{ old('spesialis') == 'Poli Jantung' ? 'selected' : '' }}>Poli Jantung</option>
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <strong>Ruangan Praktik: <span class="text-danger">*</span></strong>
                    <select class="form-control" name="ruangan_id" id="ruanganSelect" required>
                        <option value="">- Pilih Ruangan -</option>
                        @foreach($ruangans as $ruangan)
                            <option value="{{ $ruangan->id }}" 
                                    data-nama="{{ $ruangan->namaRuangan }}"
                                    data-lokasi="{{ $ruangan->lokasiRuangan }}"
                                    data-kode="{{ $ruangan->kodeRuangan }}"
                                    data-daya="{{ $ruangan->dayaTampung }}"
                                    {{ old('ruangan_id') == $ruangan->id ? 'selected' : '' }}>
                                {{ $ruangan->kodeRuangan }} - {{ $ruangan->namaRuangan }} ({{ $ruangan->lokasiRuangan }})
                                @if($ruangan->dayaTampung <= 0)
                                    - PENUH
                                @else
                                    - Daya Tampung: {{ $ruangan->dayaTampung }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Pilih ruangan tempat dokter akan praktik. Lokasi praktik akan otomatis terisi.</small>
                </div>
            </div>

            <!-- Info Ruangan yang Dipilih -->
            <div class="col-md-12" id="ruanganInfo" style="display: none;">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Info Ruangan Terpilih:</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td width="150px"><strong>Kode Ruangan:</strong></td>
                            <td id="infoKode">-</td>
                        </tr>
                        <tr>
                            <td><strong>Nama Ruangan:</strong></td>
                            <td id="infNama">-</td>
                        </tr>
                        <tr>
                            <td><strong>Lokasi:</strong></td>
                            <td id="infoLokasi">-</td>
                        </tr>
                        <tr>
                            <td><strong>Daya Tampung:</strong></td>
                            <td id="infoDaya">-</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td id="infoStatus">-</td>
                        </tr>
                    </table>
                    <small class="text-muted">
                        <i class="fas fa-lightbulb"></i> 
                        Dokter akan praktik di ruangan ini dan lokasiPraktik akan otomatis diisi dengan nama ruangan.
                    </small>
                </div>
            </div>

            <!-- Field Hidden untuk lokasiPraktik (akan otomatis terisi) -->
            <input type="hidden" name="lokasiPraktik" id="lokasiPraktikHidden">

            <div class="col-md-6">
                <div class="form-group">
                    <strong>Jam Praktik: <span class="text-danger">*</span></strong>
                    <input type="time" class="form-control" name="jamPraktik" value="{{ old('jamPraktik') }}" required>
                    <small class="text-muted">Jam mulai praktik dokter</small>
                </div>
            </div>

            <div class="col-md-12 text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Simpan Data Dokter
                </button>
                <a href="{{ route('dktr.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        
        // Handle perubahan selection ruangan
        $('#ruanganSelect').change(function() {
            var selectedOption = $(this).find('option:selected');
            
            if (selectedOption.val()) {
                var kodeRuangan = selectedOption.data('kode');
                var namaRuangan = selectedOption.data('nama');
                var lokasiRuangan = selectedOption.data('lokasi');
                var dayaTampung = selectedOption.data('daya');
                var status = dayaTampung > 0 ? 'TERSEDIA (' + dayaTampung + ' tempat)' : 'PENUH (0 tempat)';
                var statusClass = dayaTampung > 0 ? 'text-success' : 'text-danger';
                
                // Update info ruangan
                $('#infoKode').text(kodeRuangan);
                $('#infNama').text(namaRuangan);
                $('#infoLokasi').text(lokasiRuangan);
                $('#infoDaya').text(dayaTampung);
                $('#infoStatus').html('<span class="' + statusClass + '">' + status + '</span>');
                
                // Set hidden field lokasiPraktik dengan nama ruangan
                $('#lokasiPraktikHidden').val(namaRuangan);
                
                // Tampilkan info ruangan
                $('#ruanganInfo').show();
            } else {
                // Sembunyikan info ruangan jika tidak ada yang dipilih
                $('#ruanganInfo').hide();
                $('#lokasiPraktikHidden').val('');
            }
        });

        // Trigger change event jika ada old value
        if ($('#ruanganSelect').val()) {
            $('#ruanganSelect').trigger('change');
        }

        // Validasi sebelum submit
        $('form').on('submit', function(e) {
            var ruanganId = $('#ruanganSelect').val();
            
            if (!ruanganId) {
                e.preventDefault();
                alert('Silakan pilih ruangan praktik untuk dokter!');
                return false;
            }
            
            // Konfirmasi submit
            var konfirmasi = confirm('Apakah data dokter sudah benar dan ingin disimpan?');
            if (!konfirmasi) {
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    });
    </script>
@endsection