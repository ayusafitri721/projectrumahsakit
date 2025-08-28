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
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1a4480, #007a99);
        }
        .btn-primary:disabled {
            background: linear-gradient(135deg, #6c757d, #6c757d);
            cursor: not-allowed;
        }
        a {
            color: #00a8cc;
            text-decoration: none;
        }
        a:hover {
            color: #2c5aa0;
        }
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
        .loading-text {
            margin-left: 8px;
        }
        
        /* Modal Styles */
        .modal-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .modal-content {
            border-radius: 15px;
            border: none;
        }
        .success-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .success-icon.email {
            color: #007bff;
        }
        .success-icon.password {
            color: #28a745;
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
                        <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $email ?? old('email') }}" required readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-lock me-2"></i>Password Baru</label>
                                <input type="password" class="form-control" name="password" id="password" required minlength="8" placeholder="Minimal 8 karakter">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-lock me-2"></i>Konfirmasi Password</label>
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required minlength="8" placeholder="Ulangi password baru">
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-2" id="resetSubmitBtn">
                                <span id="resetBtnText">
                                    <i class="fas fa-sync-alt me-2"></i> Reset Password
                                </span>
                                <span id="resetBtnLoading" style="display: none;">
                                    <div class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></div>
                                    <span class="loading-text">Memproses...</span>
                                </span>
                            </button>
                        </form>
                    @else
                        {{-- Form Lupa Password --}}
                        <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-envelope me-2"></i>Alamat Email</label>
                                <input type="email" class="form-control" name="email" required placeholder="Masukkan email yang terdaftar">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2" id="forgotSubmitBtn">
                                <span id="forgotBtnText">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Link Reset
                                </span>
                                <span id="forgotBtnLoading" style="display: none;">
                                    <div class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></div>
                                    <span class="loading-text">Mengirim...</span>
                                </span>
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

<!-- Success Modal untuk Email Reset -->
<div class="modal fade" id="emailSuccessModal" tabindex="-1" aria-labelledby="emailSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailSuccessModalLabel">
                    <i class="fas fa-envelope-circle-check me-2"></i>Email Berhasil Dikirim
                </h5>
            </div>
            <div class="modal-body text-center">
                <div class="success-icon email">
                    <i class="fas fa-envelope-circle-check"></i>
                </div>
                <h5>Link Reset Password Terkirim!</h5>
                <p class="text-muted mb-0">Silakan cek email Anda dan klik link yang telah dikirim untuk melakukan reset password.</p>
                <small class="text-muted">Jika tidak ada di inbox, coba cek folder spam/junk.</small>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="fas fa-check me-2"></i>Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal untuk Reset Password -->
<div class="modal fade" id="passwordSuccessModal" tabindex="-1" aria-labelledby="passwordSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordSuccessModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Reset Password Berhasil
                </h5>
            </div>
            <div class="modal-body text-center">
                <div class="success-icon password">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h5>Password Berhasil Direset!</h5>
                <p class="text-muted mb-0">Password Anda telah berhasil diubah. Silakan login dengan password baru Anda.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt me-2"></i>Login Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements untuk form reset password
    const resetForm = document.getElementById('resetPasswordForm');
    const resetSubmitBtn = document.getElementById('resetSubmitBtn');
    const resetBtnText = document.getElementById('resetBtnText');
    const resetBtnLoading = document.getElementById('resetBtnLoading');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');

    // Elements untuk form forgot password
    const forgotForm = document.getElementById('forgotPasswordForm');
    const forgotSubmitBtn = document.getElementById('forgotSubmitBtn');
    const forgotBtnText = document.getElementById('forgotBtnText');
    const forgotBtnLoading = document.getElementById('forgotBtnLoading');

    // Handle Reset Password Form
    if (resetForm) {
        resetForm.addEventListener('submit', function(e) {
            // Validasi password matching
            if (passwordInput.value !== confirmPasswordInput.value) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                return;
            }

            // Show loading state - CUMA BUTTON AJA
            resetSubmitBtn.disabled = true;
            resetBtnText.style.display = 'none';
            resetBtnLoading.style.display = 'inline-block';
        });

        // Real-time password confirmation validation
        if (confirmPasswordInput && passwordInput) {
            confirmPasswordInput.addEventListener('input', function() {
                if (this.value && passwordInput.value && this.value !== passwordInput.value) {
                    this.setCustomValidity('Password tidak cocok');
                } else {
                    this.setCustomValidity('');
                }
            });

            passwordInput.addEventListener('input', function() {
                if (confirmPasswordInput.value && this.value && confirmPasswordInput.value !== this.value) {
                    confirmPasswordInput.setCustomValidity('Password tidak cocok');
                } else {
                    confirmPasswordInput.setCustomValidity('');
                }
            });
        }
    }

    // Handle Forgot Password Form - YANG PENTING INI!
    if (forgotForm) {
        forgotForm.addEventListener('submit', function(e) {
            // Show loading state - CUMA BUTTON AJA, INPUT JANGAN DISENTUH!
            forgotSubmitBtn.disabled = true;
            forgotBtnText.style.display = 'none';
            forgotBtnLoading.style.display = 'inline-block';
        });
    }

    // Check for success messages and show appropriate modal
    @if(session('status'))
        @if(isset($isReset) && $isReset)
            // Show password reset success modal
            const passwordSuccessModal = new bootstrap.Modal(document.getElementById('passwordSuccessModal'));
            passwordSuccessModal.show();
        @else
            // Show email sent success modal
            const emailSuccessModal = new bootstrap.Modal(document.getElementById('emailSuccessModal'));
            emailSuccessModal.show();
        @endif
    @endif
});
</script>
</body>
</html>