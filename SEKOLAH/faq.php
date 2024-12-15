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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantuan / FAQ</title>
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

        .main-content {
            margin-left: 260px;
            margin-top: 80px;
            padding: 30px;
            flex: 1;
            background: rgba(43, 41, 41, 0.62);
            border-radius: 12px;
            color: #fff;
            animation: fadeIn 1s ease-out;
        }

        .faq-container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            color: #333;
        }

        .faq-container h2 {
            text-align: center;
            color: #4e6f47;
            margin-bottom: 20px;
        }

        .faq-item {
            margin-bottom: 15px;
        }

        .faq-item h3 {
            color: #6b8e65;
            margin-bottom: 5px;
        }

        .faq-item p {
            font-size: 14px;
            line-height: 1.6;
        }

        .contact-section {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Menu User</h3>
        <a href="dashboard_user.php">🏠 Dashboard</a>
        <a href="jadwal.php">📅 Jadwal</a>
        <a href="tugas.php">📝 Tugas</a>
        <a href="peserta.php">👥 Peserta Kelas</a>
        <a href="pengumuman.php">📢 Pengumuman</a>
        <a href="faq.php">❓ Bantuan/FAQ</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <!-- Header -->
    <div class="header">
        <h1>Bantuan / FAQ</h1>
        <div class="profile-container">
            <a href="profile.php">
                <img src="foto.jpg" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid #fff;">
                <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="faq-container">
            <h2>Frequently Asked Questions</h2>

            <div class="faq-item">
                <h3>Bagaimana cara melihat jadwal pelajaran?</h3>
                <p>Anda bisa melihat jadwal pelajaran dengan mengklik menu <strong>Jadwal</strong> di sidebar.</p>
            </div>

            <div class="faq-item">
                <h3>Bagaimana cara mengumpulkan tugas sekolah?</h3>
                <p>Tugas sekolah dapat dilihat dan diunggah di halaman <strong>Tugas</strong>. Pastikan untuk mengumpulkan sebelum batas waktu yang ditentukan.</p>
            </div>

            <div class="faq-item">
                <h3>Bagaimana cara melihat peserta kelas?</h3>
                <p>Anda dapat melihat daftar peserta kelas dengan mengklik menu <strong>Peserta Kelas</strong> di sidebar.</p>
            </div>

            <div class="faq-item">
                <h3>Bagaimana cara menghubungi admin sekolah?</h3>
                <p>Anda dapat menghubungi admin sekolah melalui kontak yang tertera di bawah atau halaman utama.</p>
            </div>

            <div class="contact-section">
                <h3>Masih ada pertanyaan?</h3>
                <p>Hubungi kami melalui:</p>
                <p>📞 <strong>085242358500</strong> | 📧 <strong>sma01pasangkayu@gmail.com</strong></p>
            </div>
        </div>
    </div>
</body>
</html>
