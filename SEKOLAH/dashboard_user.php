<?php
session_start();
include 'db.php';

// Timeout Session
$timeout_duration = 120;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: index.php?timeout=true");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

// Ambil data sekolah
$sekolah_query = $conn->query("SELECT * FROM sekolah LIMIT 1");
$sekolah = $sekolah_query->fetch_assoc();

// Ambil data jumlah siswa dalam kelas user
$user_query = $conn->query("SELECT kelas FROM users WHERE username = '{$_SESSION['username']}'");
$user = $user_query->fetch_assoc();
$kelas = $user['kelas'];

$peserta_query = $conn->query("SELECT COUNT(*) as total_siswa FROM users WHERE kelas = '$kelas' AND role = 'user'");
$peserta = $peserta_query->fetch_assoc();
$total_siswa = $peserta['total_siswa'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: url('th.jpg') no-repeat center center fixed;
            background-size: cover;
            animation: fadeIn 1s ease-out;
        }

        /* Animasi Fade-in */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: -1;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(to bottom right, #cdeac0, #6b8e65);
            height: 100%;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            animation: fadeIn 1s ease-out;
        }

        .sidebar h3 {
            text-align: center;
            color: #4e6f47;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            padding: 12px;
            margin: 10px 0;
            text-decoration: none;
            color: #555;
            background: #e8f5e9;
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            color: #fff;
        }

        .header {
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            position: fixed;
            top: 0;
            left: 260px;
            width: calc(100% - 260px);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            z-index: 10;
            animation: fadeIn 1s ease-out;
        }

        .header-left h1 {
            margin: 0;
            font-size: 24px;
        }

        .header-left p {
            margin: 0;
            font-size: 16px;
            color: #e0e0e0;
        }

        .profile-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-container img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .profile-container strong {
            color: white;
            font-size: 16px;
        }

        .main-content {
            margin-left: 260px;
            margin-top: 80px;
            padding: 30px;
            flex: 1;
            background: rgba(43, 41, 41, 0.62);
            border-radius: 12px;
            animation: fadeIn 1s ease-out;
        }

        /* Cards Animation */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: linear-gradient(to bottom right, #cdeac0, #6b8e65);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-out;
            animation-fill-mode: forwards;
        }

        iframe {
            width: 100%;
            height: 300px;
            border-radius: 12px;
            margin-top: 20px;
        }

        .contact {
            margin-top: 20px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Menu User</h3>
        <a href="jadwal.php">📅 Jadwal</a>
        <a href="tugas.php">📝 Tugas</a>
        <a href="peserta.php">👥 Peserta Kelas</a>
        <a href="pengumuman.php">📢 Pengumuman</a>
        <a href="faq.php">❓ Bantuan/FAQ</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p><b>Sistem Informasi Sekolah SMA NEGERI 1 PASANGKAYU</b></p>
        </div>
        <div class="profile-container">
            <a href="profile.php">
                <img src="foto.jpg" alt="Profile">
                <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 style="text-align: center; color:#fff">Informasi Kelas Anda</h2>
        <p style="text-align: center; color:#fff">Total siswa di kelas Anda: <strong><?php echo $total_siswa; ?></strong></p>

        <!-- Cards -->
        <div class="card-container">
            <div class="card">
                <h2>📅 Jadwal Pelajaran</h2>
                <p>Lihat jadwal pelajaran Anda.</p>
            </div>
            <div class="card">
                <h2>📝 Tugas Sekolah</h2>
                <p>Periksa tugas-tugas terbaru dari guru.</p>
            </div>
            <div class="card">
                <h2>👥 Peserta Kelas</h2>
                <p>Total siswa: <strong><?php echo $total_siswa; ?></strong></p>
            </div>
        </div>

        <!-- Maps -->
        <h2 style="text-align: center; margin-top: 30px; color:#fff">Lokasi Sekolah</h2>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127111.67012115615!2d119.8947!3d-2.4642!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbf1ef5d38c91b5%3A0x9fa789c4af32f0fa!2sPasangkayu%2C%20Sulawesi%20Barat!5e0!3m2!1sid!2sid!4v1698232106845!5m2!1sid!2sid"></iframe>

        <!-- Kontak Sekolah -->
        <div class="contact" style="margin-top: 30px; background: #fff;">
        <h3 style="color: #4e6f47; margin-bottom: 10px;">Kontak Sekolah</h3>
        <p style="color: #333; font-size: 14px;"><strong>📞 Telepon Admin:</strong> 085242358500</p>
        <p style="color: #333; font-size: 14px;"><strong>📸 Instagram:</strong> @SMA01_PASANGKAYU</p>
        <p style="color: #333; font-size: 14px;"><strong>📧 Email:</strong> sma01pasangkayu@gmail.com</p>
    </div>
    </div>
</body>
</html>
