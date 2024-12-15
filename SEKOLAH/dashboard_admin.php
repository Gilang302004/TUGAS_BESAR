<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
// Tentukan durasi sesi dalam detik (misalnya 5 menit = 300 detik)
$timeout_duration = 2 * 60; //  menit

// Jika ada waktu terakhir aktivitas, periksa apakah sesi telah kedaluwarsa
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    // Jika sesi kedaluwarsa, hapus data sesi dan redirect ke halaman login
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
    header("Location: index.php?timeout=true");
    exit();
}

// Perbarui waktu terakhir aktivitas
$_SESSION['LAST_ACTIVITY'] = time();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard Admin</title>
    <style>
        /* General Styles */
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', Arial, sans-serif;
            background: url('th.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            line-height: 1.6;
        }

        /* Overlay */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        /* Header */
        .header {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.85);
            color: #555;
            padding: 20px 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-bottom: 4px solid #6b8e65;
            animation: fadeInDown 1s ease-in-out;
        }

        .header h1 {
            margin: 0;
            font-size: 30px;
            font-weight: 700;
            color: #4e6f47;
        }

        .dropbtn {
            background: linear-gradient(to right, #8ca37c, #6b8e65);
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .dropbtn:hover {
            background: #4e6f47;
        }

        .dropdown-content {
            display: none; /* Default: sembunyikan */
            position: absolute;
            top: 50px;
            left: 0;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
            width: 180px;
            z-index: 1;
        }

        .dropdown-content a {
            display: block;
            color: #4e6f47;
            padding: 12px 15px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }

        .dropdown-content a:hover {
            background-color: #6b8e65;
            color: white;
        }

        /* Settings Menu */
        .settings {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10; /* Pastikan berada di atas */
        }

        .settings .dropbtn {
            background: linear-gradient(to right, #8ca37c, #6b8e65);
            border: none;
            color: #fff;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: background 0.3s, transform 0.3s;
        }

        .settings .dropbtn:hover {
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            transform: scale(1.05);
        }

        .settings .dropdown-content {
            display: none;
            position: absolute;
            top: 45px;
            left: 0;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 180px;
        }

        .settings .dropdown-content a {
            display: block;
            padding: 12px 15px;
            text-decoration: none;
            color: #4e6f47;
            font-weight: 600;
            transition: background 0.3s, color 0.3s;
        }

        /* Container */
        .container {
            display: flex;
            margin: 30px;
            position: relative;
            z-index: 1;
            gap: 20px;
        }

        /* Sidebar */
        .sidebar {
            background: rgba(245, 245, 245, 0.9);
            color: #333;
            width: 260px;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .sidebar a {
            display: block;
            color: #555;
            padding: 12px;
            margin: 10px 0;
            background: #e8f5e9;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            color: #fff;
        }

        /* Content */
        .content {
            flex: 1;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        /* Card */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .card {
            background: linear-gradient(to bottom right, #cdeac0, #6b8e65);
            color: #fff;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
    </style>
    <script>
        // Fungsi untuk menampilkan/menghilangkan dropdown
        function toggleDropdown() {
            var dropdown = document.getElementById("dropdownContent");
            if (dropdown.style.display === "block") {
                dropdown.style.display = "none";
            } else {
                dropdown.style.display = "block";
            }
        }

        // Tutup dropdown jika klik di luar menu
        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === "block") {
                        openDropdown.style.display = "none";
                    }
                }
            }
        };
    </script>
</head>
<body>
    <div class="overlay"></div>

    <!-- Settings Menu -->
    <div class="settings">
        <button class="dropbtn" onclick="toggleDropdown()">⚙ Pengaturan</button>
        <div id="dropdownContent" class="dropdown-content">
            <a href="pengaturan_profil.php">Ubah Profil</a>
            <a href="pengaturan_password.php">Ubah Kata Sandi</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <!-- Header -->
    <div class="header">
        <h1>Dashboard Admin</h1>
        <p>Selamat datang, <strong><?php echo $_SESSION['username']; ?></strong>!</p>
    </div>

    <!-- Container -->
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h3>Menu Admin</h3>
            <a href="kelola_siswa.php">Kelola Siswa</a>
            <a href="kelola_guru.php">Kelola Guru</a>
            <a href="kelola_jadwal.php">Kelola Jadwal</a>
            <a href="kelola_tugas.php">Kelola Tugas</a>
            <a href="laporan.php">Laporan</a>
        </div>

        <!-- Main Content -->
        <div class="content">
            <h2>Statistik Sekolah</h2>
            <div class="card-container">
                <div class="card">
                    <h3>8</h3>
                    <p>Jumlah Siswa</p>
                </div>
                <div class="card">
                    <h3>10</h3>
                    <p>Jumlah Guru</p>
                </div>
                <div class="card">
                    <h3>20</h3>
                    <p>Jumlah Kelas</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
