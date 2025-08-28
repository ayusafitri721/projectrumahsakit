@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title mb-1">
                <i class="fas fa-door-open text-primary me-2"></i>
                Detail Ruangan
            </h2>
            <p class="text-muted mb-0">Informasi lengkap ruangan {{ $ruangan->namaRuangan }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('ruangan.edit', $ruangan->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('ruangan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Detail Card -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informasi Ruangan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Kode Ruangan -->
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-qrcode"></i>
                                </div>
                                <div class="detail-content">
                                    <label class="detail-label">Kode Ruangan</label>
                                    <div class="detail-value">{{ $ruangan->kodeRuangan }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Nama Ruangan -->
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <div class="detail-content">
                                    <label class="detail-label">Nama Ruangan</label>
                                    <div class="detail-value">{{ $ruangan->namaRuangan }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Daya Tampung -->
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="detail-content">
                                    <label class="detail-label">Daya Tampung</label>
                                    <div class="detail-value">
                                        <span class="badge bg-success fs-6 px-3 py-2">
                                            {{ $ruangan->dayaTampung }} orang
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lokasi -->
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="detail-content">
                                    <label class="detail-label">Lokasi</label>
                                    <div class="detail-value">{{ $ruangan->lokasi }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        Status Ruangan
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="status-indicator mb-3">
                        <i class="fas fa-circle text-success fa-2x"></i>
                    </div>
                    <h5 class="text-success mb-2">Tersedia</h5>
                    <p class="text-muted mb-0">Ruangan siap digunakan</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('ruangan.edit', $ruangan->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>
                            Edit Ruangan
                        </a>
                        <button class="btn btn-info" onclick="printDetails()">
                            <i class="fas fa-print me-2"></i>
                            Print Detail
                        </button>
                        <form action="{{ route('ruangan.destroy', $ruangan->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" 
                                    onclick="return confirm('Yakin ingin menghapus ruangan ini?')">
                                <i class="fas fa-trash me-2"></i>
                                Hapus Ruangan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Tambahan -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info me-2"></i>
                        Informasi Tambahan
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-calendar-alt text-muted me-2"></i>
                            <small class="text-muted">Dibuat: {{ $ruangan->created_at ? $ruangan->created_at->format('d/m/Y H:i') : '-' }}</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-edit text-muted me-2"></i>
                            <small class="text-muted">Diupdate: {{ $ruangan->updated_at ? $ruangan->updated_at->format('d/m/Y H:i') : '-' }}</small>
                        </li>
                        <li>
                            <i class="fas fa-hashtag text-muted me-2"></i>
                            <small class="text-muted">ID: {{ $ruangan->id }}</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .detail-item {
        display: flex;
        align-items: flex-start;
        padding: 1.25rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 1rem;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
        height: 100%;
    }

    .detail-item:hover {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.15);
    }

    .detail-icon {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        font-size: 1.1rem;
    }

    .detail-content {
        flex: 1;
        min-width: 0;
    }

    .detail-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--dark-color);
        word-break: break-word;
    }

    .status-indicator {
        position: relative;
        display: inline-block;
    }

    .status-indicator::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background: rgba(16, 185, 129, 0.1);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
        }
        70% {
            box-shadow: 0 0 0 15px rgba(16, 185, 129, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 1rem;
        }

        .page-header .d-flex {
            width: 100%;
            justify-content: center;
        }

        .detail-item {
            padding: 1rem;
        }

        .detail-icon {
            width: 45px;
            height: 45px;
            margin-right: 0.75rem;
        }

        .detail-value {
            font-size: 1rem;
        }
    }
</style>

<script>
    function printDetails() {
        const printWindow = window.open('', '_blank');
        const ruanganData = {
            kode: '{{ $ruangan->kodeRuangan }}',
            nama: '{{ $ruangan->namaRuangan }}',
            dayaTampung: '{{ $ruangan->dayaTampung }}',
            lokasi: '{{ $ruangan->lokasi }}',
            created: '{{ $ruangan->created_at ? $ruangan->created_at->format("d/m/Y H:i") : "-" }}',
            updated: '{{ $ruangan->updated_at ? $ruangan->updated_at->format("d/m/Y H:i") : "-" }}'
        };

        printWindow.document.write(`
            <html>
            <head>
                <title>Detail Ruangan - ${ruanganData.nama}</title>
                <style>
                    body { 
                        font-family: Arial, sans-serif; 
                        margin: 40px; 
                        line-height: 1.6; 
                        color: #333;
                    }
                    .header { 
                        text-align: center; 
                        margin-bottom: 40px; 
                        border-bottom: 3px solid #2563eb; 
                        padding-bottom: 20px; 
                    }
                    .header h1 { color: #2563eb; margin-bottom: 10px; }
                    .detail-row { 
                        margin: 20px 0; 
                        display: flex; 
                        border-bottom: 1px solid #e2e8f0;
                        padding-bottom: 15px;
                    }
                    .label { 
                        font-weight: bold; 
                        width: 180px; 
                        color: #64748b;
                    }
                    .value { 
                        flex: 1; 
                        color: #1e293b;
                        font-weight: 500;
                    }
                    .footer { 
                        margin-top: 50px; 
                        text-align: center; 
                        font-size: 12px; 
                        color: #64748b; 
                        border-top: 1px solid #e2e8f0;
                        padding-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>DETAIL RUANGAN</h1>
                    <h2>${ruanganData.nama}</h2>
                </div>
                <div class="content">
                    <div class="detail-row">
                        <span class="label">Kode Ruangan:</span>
                        <span class="value">${ruanganData.kode}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Nama Ruangan:</span>
                        <span class="value">${ruanganData.nama}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Daya Tampung:</span>
                        <span class="value">${ruanganData.dayaTampung} orang</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Lokasi:</span>
                        <span class="value">${ruanganData.lokasi}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Dibuat:</span>
                        <span class="value">${ruanganData.created}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Diperbarui:</span>
                        <span class="value">${ruanganData.updated}</span>
                    </div>
                </div>
                <div class="footer">
                    <p>Dicetak pada: ${new Date().toLocaleString('id-ID')}</p>
                    <p>© ${new Date().getFullYear()} {{ config('app.name') }}</p>
                </div>
            </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.print();
    }
</script>
@endsection