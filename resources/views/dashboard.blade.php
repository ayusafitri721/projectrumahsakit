<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $user->name }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f7fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: linear-gradient(135deg, #2c5aa0, #00a8cc);
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-3px);
        }
        .card.bg-primary, .card.bg-success, .card.bg-warning, .card.bg-danger, .card.bg-info {
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .card.bg-primary { background: linear-gradient(135deg, #4e73df, #224abe); }
        .card.bg-success { background: linear-gradient(135deg, #1cc88a, #0e9e6e); }
        .card.bg-warning { background: linear-gradient(135deg, #f6c23e, #dda20a); }
        .card.bg-danger { background: linear-gradient(135deg, #e74a3b, #be2617); }
        .card.bg-info { background: linear-gradient(135deg, #36b9cc, #258f9b); }
        footer {
            background: #2c5aa0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="fas fa-hospital-user me-2"></i>AmryHospital
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                            @else
                                <i class="fas fa-user-circle fa-lg me-2"></i>
                            @endif
                            {{ $user->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Welcome Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="card-title mb-0">Selamat Datang, {{ $user->name }} 🎉</h2>
                                <p class="card-text mt-2">
                                    <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                                    @if($user->username)
                                        <br><i class="fas fa-at me-2"></i>{{ $user->username }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4 text-center">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" 
                                         class="rounded-circle border border-white shadow" 
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-white text-primary d-inline-flex align-items-center justify-content-center shadow" 
                                         style="width: 100px; height: 100px; font-size: 3rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="mb-3 fw-bold">Menu Utama</h3>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 text-center p-3">
                    <i class="fas fa-user-md text-primary mb-3" style="font-size: 3rem;"></i>
                    <h5 class="card-title">Data Dokter</h5>
                    <p class="card-text">Kelola informasi dokter dan spesialis</p>
                    <a href="{{ route('dktr.index') }}" class="btn btn-primary">Lihat Data</a>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 text-center p-3">
                    <i class="fas fa-calendar-alt text-success mb-3" style="font-size: 3rem;"></i>
                    <h5 class="card-title">Jadwal</h5>
                    <p class="card-text">Lihat dan atur jadwal konsultasi</p>
                    <a href="#" class="btn btn-success">Lihat Jadwal</a>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 text-center p-3">
                    <i class="fas fa-chart-bar text-warning mb-3" style="font-size: 3rem;"></i>
                    <h5 class="card-title">Laporan</h5>
                    <p class="card-text">Lihat statistik dan laporan sistem</p>
                    <a href="#" class="btn btn-warning text-white">Lihat Laporan</a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-12">
                <h3 class="mb-3 fw-bold">Statistik</h3>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h4>0</h4>
                            <span>Total Dokter</span>
                        </div>
                        <i class="fas fa-user-md fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h4>0</h4>
                            <span>Konsultasi Hari Ini</span>
                        </div>
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h4>0</h4>
                            <span>Pending</span>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-white">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h4>{{ \App\Models\User::count() }}</h4>
                            <span>Total Users</span>
                        </div>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-white text-center py-3 mt-5 shadow-lg">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Healthcare App. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
