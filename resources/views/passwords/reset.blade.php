<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .card-header {
            background: linear-gradient(135deg, #2c5aa0, #00a8cc);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            text-align: center;
            padding: 1.5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2c5aa0, #00a8cc);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1a4480, #007a99);
        }
        .form-label i {
            margin-right: 6px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-unlock-alt me-2"></i> Reset Password</h4>
                    <p class="mb-0 text-white-50">Silakan buat password baru</p>
                </div>
                <div class="card-body p-4">

                    {{-- Error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-envelope"></i>Email</label>
                            <input type="email" class="form-control" name="email"
                                   value="{{ $email ?? old('email') }}" required readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-lock"></i>Password Baru</label>
                            <input type="password" class="form-control" name="password"
                                   required minlength="8" placeholder="Minimal 8 karakter">
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-lock"></i>Konfirmasi Password</label>
                            <input type="password" class="form-control" name="password_confirmation"
                                   required minlength="8" placeholder="Ulangi password baru">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-sync-alt me-2"></i>Reset Password
                        </button>
                    </form>

                    <hr class="my-4">
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="d-block mb-2">
                            <i class="fas fa-sign-in-alt me-1"></i>Kembali ke Login
                        </a>
                        <a href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>Kembali ke Home
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
