<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
                        <h4 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i> Login</h4>
                        <p class="text-white-50 mb-0">Masuk untuk melanjutkan</p>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-user me-2"></i>Email atau Username</label>
                                <input type="text" name="login" class="form-control" value="{{ old('login') }}" placeholder="Masukkan email atau username" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                            </div>
                            
                            <!-- Lupa Password link di kanan atas button -->
                            <div class="d-flex justify-content-end mb-2">
                                <a href="{{ route('password.request') }}">
                                    <i class="fas fa-unlock-alt me-1"></i> Lupa password?
                                </a>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </form>

                        <hr class="my-4">
                        <div class="text-center">
                            <p class="mb-2">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
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
