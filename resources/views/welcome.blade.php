<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Rumah Sakit</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
        }
        
        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }
        
        .nav-menu a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .nav-menu a:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }
        
        .nav-auth-buttons {
            display: flex;
            gap: 12px;
            margin-left: 20px;
        }
        
        .nav-btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .nav-btn-login {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            backdrop-filter: blur(10px);
        }
        
        .nav-btn-login:hover {
          background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        .nav-btn-register {
            background: transparent;
            color: white;
        }
        
        .nav-btn-register:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        /* Hero Section */
        .hero {
            position: relative;
        }

        .hero-image-container {
            position: relative;
            width: 100vw;
            height: 100vh;
            margin-left: calc(-50vw + 50%);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-image-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 3;
            color: white;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .hero-overlay h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            color: #fff;
        }

        .hero-overlay p {
            font-size: 1.4rem;
            margin-bottom: 3rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
            opacity: 0.95;
            color: #f1f1f1;
        }

        .btn-group-custom {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 40px;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
        }
        
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #152d5a, #1e3c72);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(30, 60, 114, 0.4);
        }
        
        .btn-outline-custom {
            background: rgba(255, 255, 255, 0.9);
            color: #1e3c72;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        
        .btn-outline-custom:hover {
            background: white;
            color: #1e3c72;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
        }
        
        .hero .btn-outline-custom {
            background: white;
            color: #1e3c72;
            border: 2px solid #1e3c72;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .hero .btn-outline-custom:hover {
            background: #1e3c72;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(30, 60, 114, 0.3);
        }
        
        /* Features Section */
        .features {
            padding: 80px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .features h2 {
            text-align: center;
            margin-bottom: 3rem;
            color: #1e3c72;
            font-size: 2.5rem;
            font-weight: 700;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            text-align: center;
            padding: 40px 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border-top: 4px solid #1e3c72;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: #1e3c72;
            margin-bottom: 25px;
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #1e3c72;
        }
        
        .feature-card p {
            color: #666;
            line-height: 1.7;
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            text-align: center;
            padding: 3rem 20px;
            margin-top: 50px;
        }
        
        .footer p {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        @media (max-width: 768px) {
            .hero-overlay h1 {
                font-size: 2.2rem;
                flex-direction: column;
                gap: 10px;
            }
            
            .hero-overlay p {
                font-size: 1.1rem;
            }
            
            .nav-menu {
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav-auth-buttons {
                margin-left: 0;
                margin-top: 15px;
            }
            
            .btn-group-custom {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 280px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-hospital-alt"></i>
                AmryHospital
            </div>
            <ul class="nav-menu">
                <li><a href="#home">Home</a></li>
                <li><a href="#tentang">Tentang</a></li>
                <li><a href="#layanan">Layanan</a></li>
                <li><a href="#kontak">Kontak</a></li>
                <div class="nav-auth-buttons">
                    <a href="/login" class="nav-btn nav-btn-login">
                        <i class="fas fa-sign-in-alt"></i>Login
                    </a>
                    <a href="/register" class="nav-btn nav-btn-register">
                        <i class="fas fa-user-plus"></i>Daftar
                    </a>
                </div>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-image-container">
            <img src="https://images.unsplash.com/photo-1551190822-a9333d879b1f?w=1920&h=1080&fit=crop" alt="Rumah Sakit">
            
            <!-- Overlay teks -->
            <div class="hero-overlay">
                <h1>
                    <i class="fas fa-hospital-alt"></i>
                    Sistem Informasi Rumah Sakit Amry
                </h1>
                <p>Platform digital untuk manajemen rumah sakit yang efisien dan modern</p>
                
                <div class="btn-group-custom">
                    <a href="/login" class="btn btn-primary-custom">
                        <i class="fas fa-sign-in-alt"></i>Masuk ke Sistem
                    </a>
                    <a href="#layanan" class="btn btn-outline-custom">
                        <i class="fas fa-info-circle"></i>Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="layanan">
        <h2>Fitur Utama</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <h3>Manajemen Dokter</h3>
                <p>Kelola data dokter dengan mudah termasuk spesialisasi, jadwal praktik, dan informasi kontak</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-hospital"></i>
                </div>
                <h3>Manajemen Ruangan</h3>
                <p>Sistem manajemen ruangan rumah sakit dengan informasi daya tampung dan lokasi yang lengkap</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <h3>Sistem Login Aman</h3>
                <p>Sistem autentikasi yang aman dengan fitur registrasi otomatis dan manajemen pengguna terintegrasi</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-key"></i>
                </div>
                <h3>Reset Password</h3>
                <p>Fitur reset password yang aman dengan validasi email untuk memudahkan pemulihan akun pengguna</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Sistem Informasi Rumah Sakit Amry. All rights reserved.</p>
    </footer>
</body>
</html>