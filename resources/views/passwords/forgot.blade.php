<!DOCTYPE html>
<html>
<head>
    <title>{{ isset($isReset) ? 'Reset Password' : 'Lupa Password' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border: none;
            border-radius: 15px;
        }
        .card-header {
            background: linear-gradient(135deg, #2c5aa0, #00a8cc);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            text-align: center;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2c5aa0, #00a8cc);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1a4480, #007a99);
        }
        a {
            color: #00a8cc;
            text-decoration: none;
        }
        a:hover {
            color: #2c5aa0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas {{ isset($isReset) ? 'fa-unlock-alt' : 'fa-envelope' }} me-2"></i>
                        {{ isset($isReset) ? 'Reset Password' : 'Lupa Password' }}
                    </h4>
                    <p class="text-white-50 mb-0">
                        {{ isset($isReset) ? 'Silakan buat password baru' : 'Masukkan email untuk kirim link reset' }}
                    </p>
                </div>
                <div class="card-body">

                    {{-- Notif sukses --}}
                    @if (session('status'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                        </div>
                    @endif

                    {{-- Notif error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(isset($isReset) && $isReset)
                        {{-- Form Reset Password --}}
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $email ?? old('email') }}" required readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-lock me-2"></i>Password Baru</label>
                                <input type="password" class="form-control" name="password" required minlength="8" placeholder="Minimal 8 karakter">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-lock me-2"></i>Konfirmasi Password</label>
                                <input type="password" class="form-control" name="password_confirmation" required minlength="8" placeholder="Ulangi password baru">
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="fas fa-sync-alt me-2"></i> Reset Password
                            </button>
                        </form>
                    @else
                        {{-- Form Lupa Password --}}
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-envelope me-2"></i>Alamat Email</label>
                                <input type="email" class="form-control" name="email" required placeholder="Masukkan email yang terdaftar">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Link Reset
                            </button>
                        </form>
                    @endif

                    <hr class="my-4">
                    <div class="text-center">
                        <p class="mb-2"><a href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i>Kembali ke Login</a></p>
                        <p class="mb-0"><a href="{{ route('home') }}"><i class="fas fa-home me-1"></i>Kembali ke Home</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
