<?php
session_start();
include 'db.php';

// Tentukan durasi sesi dalam detik (5 menit)
$timeout_duration = 120;

// Cek timeout sesi
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
    header("Location: index.php?timeout=true");
    exit();
}

// Update waktu terakhir aktivitas
$_SESSION['LAST_ACTIVITY'] = time();

// Cek apakah session sudah terisi dengan benar
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

// Ambil data user berdasarkan username
$user_query = $conn->query("SELECT * FROM users WHERE username = '" . $_SESSION['username'] . "'");
$user_data = $user_query->fetch_assoc();

// Jika data user tidak ditemukan, logout
if (!$user_data) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Ambil jadwal mata pelajaran berdasarkan kelas user
$jadwal_query = $conn->query("SELECT * FROM jadwal WHERE kelas = '" . $user_data['kelas'] . "'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Mata Pelajaran</title>
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
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(3px);
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
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            color: white;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4e6f47;
        }

        .sidebar a {
            display: block;
            padding: 12px;
            margin: 10px 0;
            text-decoration: none;
            color: #fff;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            background: linear-gradient(to right, #6b8e65, #4e6f47);
        }

        .header {
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            position: fixed;
            top: 0;
            left: 260px;
            width: calc(100% - 260px);
            padding: 15px 20px;
            color: white;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 24px;
            font-weight: bold;
            animation: fadeIn 1s ease-out;
        }

        .main-content {
            width: 70%;
            margin-left: 330px;
            margin-top: 80px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-out;
        }

        h2 {
            color: #4e6f47;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background: #6b8e65;
            color: white;
        }

        table tr:nth-child(even) {
            background: #f2f2f2;
        }

        table tr:hover {
            background-color: #e8f5e9;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Menu</h2>
        <a href="dashboard_user.php">🏠 Dashboard</a>
        <a href="tugas.php">📝 Tugas</a>
        <a href="peserta.php">👥 Peserta Kelas</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <!-- Header -->
    <div class="header">
        Jadwal Mata Pelajaran
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Jadwal Mata Pelajaran - <?php echo htmlspecialchars($user_data['kelas']); ?></h2>
        <?php if ($jadwal_query->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Hari</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                </tr>
                <?php while ($row = $jadwal_query->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['hari']); ?></td>
                        <td><?php echo htmlspecialchars($row['jam_mulai']); ?></td>
                        <td><?php echo htmlspecialchars($row['jam_selesai']); ?></td>
                        <td><?php echo htmlspecialchars($row['mata_pelajaran']); ?></td>
                        <td><?php echo htmlspecialchars($row['guru']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p style="text-align: center;">Belum ada jadwal mata pelajaran untuk kelas Anda.</p>
        <?php endif; ?>
    </div>
</body>
</html>
