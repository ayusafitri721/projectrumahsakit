<!DOCTYPE html>
<html>
<head>
    <title>Registrasi Berhasil</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4><i class="fas fa-check"></i> Registrasi Berhasil!</h4>
                    </div>
                    <div class="card-body text-center">
                        <div class="alert alert-success">
                            Selamat! Akun Anda telah berhasil dibuat.
                        </div>
                        
                        <!-- Tampilkan Data User -->
                        <div class="mb-4">
                            <div id="currentPhoto">
                                @if($user->profile_photo)
                                    <img id="profileImage" src="{{ asset('storage/' . $user->profile_photo) }}" 
                                         class="rounded-circle mb-3" width="100" height="100" 
                                         style="object-fit: cover;">
                                @else
                                    <div id="defaultAvatar" class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                         style="width: 100px; height: 100px;">
                                        <i class="fas fa-user fa-3x text-white"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Upload Foto Form -->
                            <form id="uploadForm" method="POST" enctype="multipart/form-data" class="mb-3">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <div class="mb-2">
                                    <label class="form-label">Upload/Ganti Foto Profil:</label>
                                    <input type="file" id="photoInput" name="profile_photo" class="form-control" accept="image/*">
                                </div>
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-upload"></i> Upload Foto
                                </button>
                            </form>
                            
                            <!-- Loading indicator -->
                            <div id="loadingIndicator" class="d-none">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                Mengupload...
                            </div>
                            
                            <h5>{{ $user->name }}</h5>
                            <p class="text-muted">{{ $user->email }}</p>
                            <p><strong>Username:</strong> {{ $user->username }}</p>
                            <p><strong>Role:</strong> {{ $user->role }}</p>
                        </div>
                        
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Login Sekarang
                        </a>
                        
                        <div class="mt-3">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                Kembali ke Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview langsung ganti foto yang bulat
        document.getElementById('photoInput').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const currentPhoto = document.getElementById('currentPhoto');
                    currentPhoto.innerHTML = `
                        <img id="profileImage" src="${event.target.result}" 
                             class="rounded-circle mb-3" width="100" height="100" 
                             style="object-fit: cover;">
                    `;
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Handle form upload dengan AJAX
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const loadingIndicator = document.getElementById('loadingIndicator');
            const uploadForm = document.getElementById('uploadForm');
            
            // Show loading
            loadingIndicator.classList.remove('d-none');
            uploadForm.style.opacity = '0.5';
            
            fetch('{{ route("upload.photo") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Hide loading
                loadingIndicator.classList.add('d-none');
                uploadForm.style.opacity = '1';
                
                if (data.success) {
                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                    alertDiv.innerHTML = `
                        <i class="fas fa-check-circle"></i> ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('.mb-4'));
                    
                    // Clear file input
                    document.getElementById('photoInput').value = '';
                } else {
                    throw new Error(data.message || 'Upload gagal');
                }
            })
            .catch(error => {
                // Hide loading
                loadingIndicator.classList.add('d-none');
                uploadForm.style.opacity = '1';
                
                // Show error message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                alertDiv.innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i> Error: ${error.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('.mb-4'));
                
                console.error('Upload error:', error);
            });
        });
    </script>
</body>
</html>