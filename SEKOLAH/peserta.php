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

if (!$user_data) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Ambil peserta berdasarkan kelas user
$kelas = $user_data['kelas'];
$peserta_query = $conn->query("SELECT nama, gender, email FROM siswa WHERE kelas = '$kelas'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peserta Kelas</title>
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

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(to bottom right, #cdeac0, #6b8e65);
            height: 100%;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            padding: 12px;
            margin: 10px 0;
            text-decoration: none;
            color: white;
            border-radius: 8px;
            text-align: center;
            background: rgba(255, 255, 255, 0.2);
            transition: all 0.3s;
        }

        .sidebar a:hover {
            background: linear-gradient(to right, #6b8e65, #4e6f47);
        }

        /* Header */
        .header {
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            position: fixed;
            top: 0;
            left: 260px;
            width: calc(100% - 260px);
            padding: 15px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-out;
        }

        /* Main Content */
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

        .main-content h2{
            color: #4e6f47;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            color:rgb(255, 255, 255);
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }

        table th {
            background: #6b8e65;
            color: white;
        }

        table tr:nth-child(even) {
            background: #f9f9f9;
        }

        table tr:hover {
            background: #e8f5e9;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Menu</h2>
        <a href="dashboard_user.php">🏠 Dashboard</a>
        <a href="jadwal.php">📅 Jadwal</a>
        <a href="tugas.php">📝 Tugas</a>
        <a href="peserta.php">👥 Peserta Kelas</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <!-- Header -->
    <div class="header">
        <h2>Peserta Kelas</h2>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Peserta Kelas - <?php echo htmlspecialchars($user_data['kelas']); ?></h2>
        <?php if ($peserta_query->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Gender</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = $peserta_query->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center;">Belum ada peserta dalam kelas Anda.</p>
        <?php endif; ?>
    </div>
</body>
</html>
