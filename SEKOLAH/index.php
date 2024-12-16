<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Sistem Informasi Sekolah</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f4f4f9;
            color: #333;
            overflow-x: hidden;
        }

        /* Hero Section with Parallax Effect */
        .hero {
            position: relative;
            height: 100vh;
            background: linear-gradient(to right, rgba(108, 190, 133, 0.7), rgba(78, 111, 71, 0.7)), 
                        url('th.jpg') center/cover no-repeat fixed;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            animation: slideInDown 1.5s ease-out;
        }

        @keyframes slideInDown {
            0% { opacity: 0; transform: translateY(-50px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
            animation: fadeInText 1.8s ease-in-out;
        }

        .hero p {
            font-size: 18px;
            margin-bottom: 30px;
            color: #f1f1f1;
            animation: fadeInText 2.2s ease-in-out;
        }

        @keyframes fadeInText {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(to right, #8ca37c, #6b8e65);
            color: #fff;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .hero .btn:hover {
            transform: scale(1.1) rotate(-2deg);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.4);
        }

        /* Features Section */
        .features {
            padding: 50px 20px;
            text-align: center;
            background: #fff;
            opacity: 0;
            animation: fadeInUp 1.5s ease-in-out 0.5s forwards;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .features h2 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #4e6f47;
        }

        .feature-grid {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
        }

        .feature-box {
            background: #f9f9f9;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 250px;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .feature-box::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom right, #6b8e65, #4e6f47);
            transform: scaleY(0);
            transform-origin: bottom;
            transition: transform 0.4s ease;
            z-index: -1;
        }

        .feature-box:hover::before {
            transform: scaleY(1);
        }

        .feature-box:hover {
            color: #fff;
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .feature-box i {
            font-size: 40px;
            margin-bottom: 10px;
            color: #6b8e65;
            transition: color 0.4s ease;
        }

        .feature-box:hover i {
            color: #fff;
        }

        .feature-box h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        /* Footer */
        .footer {
            background: #4e6f47;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero">
        <h1>Selamat Datang di Sistem Informasi Sekolah</h1>
        <p>
            Akses mudah dan cepat ke jadwal, tugas, dan pengumuman sekolah. 
            Dirancang untuk pengalaman belajar yang lebih baik.
        </p>
        <a href="login.php" class="btn">Masuk ke Halaman Login</a>
    </div>

    <!-- Features Section -->
    <div class="features">
        <h2>Fitur Unggulan</h2>
        <div class="feature-grid">
            <div class="feature-box">
                <i class="fas fa-calendar-alt"></i>
                <h3>Jadwal Pelajaran</h3>
                <p>Kelola jadwal harian dengan mudah dan tepat.</p>
            </div>
            <div class="feature-box">
                <i class="fas fa-book"></i>
                <h3>Tugas Sekolah</h3>
                <p>Akses dan kelola tugas sekolah dengan efisien.</p>
            </div>
            <div class="feature-box">
                <i class="fas fa-user-friends"></i>
                <h3>Peserta Kelas</h3>
                <p>Temukan teman sekelas dan kolaborasi lebih baik.</p>
            </div>
            <div class="feature-box">
                <i class="fas fa-bullhorn"></i>
                <h3>Pengumuman</h3>
                <p>Dapatkan informasi terkini dari sekolah.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; 2024 SMA NEGERI 1 PASANGKAYU | All Rights Reserved
    </div>
</body>
</html>
