<?php
session_start();
include 'db.php';

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

// Cek akses admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Laporan Sekolah</title>
    <style>
        /* General Styles */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', Arial, sans-serif;
            background: url('th.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        /* Overlay */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: -1;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.9);
            color: #4e6f47;
            text-align: center;
            padding: 20px;
            border-bottom: 4px solid #6b8e65;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }

        /* Container */
        .container {
            margin: 30px auto;
            max-width: 90%;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        h2 {
            text-align: center;
            color: #4e6f47;
            margin-bottom: 20px;
            font-size: 24px;
            border-bottom: 2px solid #6b8e65;
            padding-bottom: 10px;
        }

        /* Back Link */
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #4e6f47;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #2e4d27;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: #f9f9f9;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        table th {
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            color: #fff;
            padding: 12px;
            text-transform: uppercase;
            font-weight: bold;
        }

        table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table tr:nth-child(even) {
            background: #f1f8e9;
        }

        table tr:hover {
            background: #e6f7d6;
            transition: background 0.3s ease;
        }

        /* Button Cetak */
        .btn-cetak {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: background 0.3s, transform 0.2s;
        }

        .btn-cetak:hover {
            background: #4e6f47;
            transform: scale(1.05);
        }

        /* Print Media */
        @media print {
            .btn-cetak, .back-link {
                display: none;
            }
            .container {
                box-shadow: none;
                background: #fff;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <!-- Header -->
    <div class="header">
        <h1>Laporan Sekolah</h1>
    </div>

    <!-- Container -->
    <div class="container">
        <a href="dashboard_admin.php" class="back-link">← Kembali ke Dashboard</a>

        <!-- Laporan Siswa -->
        <h2>Data Siswa</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Umur</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM siswa");
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                <td><?php echo htmlspecialchars($row['kelas']); ?></td>
                <td><?php echo htmlspecialchars($row['umur']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- Laporan Guru -->
        <h2>Data Guru</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Mata Pelajaran</th>
                <th>Umur</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM guru");
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                <td><?php echo htmlspecialchars($row['mata_pelajaran']); ?></td>
                <td><?php echo htmlspecialchars($row['umur']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- Laporan Jadwal -->
        <h2>Jadwal Pelajaran</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Hari</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Mata Pelajaran</th>
                <th>Guru</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM jadwal");
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['hari']); ?></td>
                <td><?php echo htmlspecialchars($row['jam_mulai']); ?></td>
                <td><?php echo htmlspecialchars($row['jam_selesai']); ?></td>
                <td><?php echo htmlspecialchars($row['mata_pelajaran']); ?></td>
                <td><?php echo htmlspecialchars($row['guru']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- Tombol Cetak -->
        <a href="#" class="btn-cetak" onclick="window.print(); return false;">Cetak Laporan</a>
    </div>
</body>
</html>
