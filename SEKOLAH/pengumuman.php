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

// Simulasi data pengumuman
$pengumuman = [
    ["title" => "Pengumpulan Tugas Akhir Semester", "date" => "2024-06-10", "content" => "Segera kumpulkan tugas akhir semester Anda sebelum tanggal <strong>10 Juni 2024</strong>. Keterlambatan pengumpulan akan mempengaruhi nilai akhir."],
    ["title" => "Libur Semester Genap", "date" => "2024-06-15", "content" => "Libur semester genap dimulai pada <strong>15 Juni 2024</strong> hingga <strong>30 Juni 2024</strong>. Kegiatan belajar mengajar akan dimulai kembali pada <strong>1 Juli 2024</strong>."],
    ["title" => "Lomba Kebersihan Kelas", "date" => "2024-06-12", "content" => "Lomba kebersihan antar kelas akan dilaksanakan pada tanggal <strong>12 Juni 2024</strong>. Mari kita jaga kebersihan dan keindahan kelas masing-masing!"],
    ["title" => "Kegiatan Ekstrakurikuler Baru", "date" => "2024-06-08", "content" => "Ekstrakurikuler baru, yaitu <strong>RoboClub</strong> dan <strong>Klub Bahasa Jepang</strong> akan segera dimulai. Pendaftaran dibuka hingga <strong>8 Juni 2024</strong>."]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman</title>
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
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            color: #333;
            animation: fadeIn 1s ease-out;
        }

        .announcement {
            margin-bottom: 20px;
            padding: 20px;
            background: #f0f8f5;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .announcement h3 {
            color: #4e6f47;
            margin-bottom: 10px;
        }

        .announcement p {
            margin-bottom: 10px;
            font-size: 14px;
            line-height: 1.6;
        }

        .announcement small {
            font-size: 12px;
            color: #888;
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
        <h1>Pengumuman Sekolah</h1>
        <div class="profile-container">
            <a href="profile.php">
                <img src="foto.jpg" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid #fff;">
                <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 style="text-align: center; color:#4e6f47;">Daftar Pengumuman</h2>
        <?php foreach ($pengumuman as $item): ?>
            <div class="announcement">
                <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                <small>Tanggal: <?php echo htmlspecialchars($item['date']); ?></small>
                <p><?php echo $item['content']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
