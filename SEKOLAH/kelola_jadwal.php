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

// Fungsi untuk mengambil jadwal berdasarkan kelas
function getJadwalByKelas($kelas, $conn) {
    $sql = "SELECT * FROM jadwal WHERE kelas='$kelas' ORDER BY hari, jam_mulai";
    $result = $conn->query($sql);
    if (!$result) {
        die("Error pada query: " . $conn->error);
    }
    return $result;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Jadwal</title>
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
            font-size: 30px;
            font-weight: bold;
        }

        /* Container */
        .container {
            margin: 20px auto;
            max-width: 90%;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .container h2 {
            text-align: center;
            color: #4e6f47;
            margin-bottom: 20px;
        }

        .kelas-container {
            margin-bottom: 30px;
        }

        h2 {
            color: #4e6f47;
            margin-bottom: 10px;
            text-align: center;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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
        }

        table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table tr:hover {
            background: #f1f8e9;
        }

        /* Buttons */
        a {
            text-decoration: none;
            color: #6b8e65;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #3c4e39;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            color: #fff;
            background: #6b8e65;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s, transform 0.2s;
        }

        .btn:hover {
            background: #4e6f47;
            transform: scale(1.05);
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #b71c1c;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <!-- Header -->
    <div class="header">
        <h1>Kelola Jadwal</h1>
    </div>

    <!-- Container -->
    <div class="container">
    <h2>Daftar Jadwal Pelajaran</h2>
        <div style="text-align: left; margin-bottom: 20px;">
            <a href="dashboard_admin.php" class="btn">← Kembali ke Dashboard</a>
            <a href="add_jadwal.php" class="btn">+ Tambah Jadwal</a>
        </div>

        <!-- Kontainer untuk Setiap Kelas -->
        <?php 
        $kelas_list = ['XII IPA 1', 'XII IPA 2', 'XII IPA 3','XII IPA 4','XII IPA 5','XI IPA 1','XI IPA 2','XI IPA 3','XI IPA 4','XI IPA 5', 'X IPA 1','X IPA 2','XI IPA 3','X IPA 4','X IPA 5', 'XII IPS 1', 'XII IPS 2', 'XII IPS 3','XII IPS 4','XII IPS 5','XI IPS 1','XI IPS 2','XI IPS 3','XI IPS 4','XI IPS 5', 'X IPS 1','X IPS 2','XI IPS 3','X IPS 4','X IPS 5',];  // Daftar kelas yang ingin ditampilkan
        foreach ($kelas_list as $kelas): 
        ?>
        <div class="kelas-container">
            <h2>Jadwal Kelas <?php echo $kelas; ?></h2>
            <table>
                <tr>
                    <th>Hari</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Aksi</th>
                </tr>
                <?php
                $jadwal = getJadwalByKelas($kelas, $conn);
                while ($row = $jadwal->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo $row['hari']; ?></td>
                    <td><?php echo $row['jam_mulai']; ?></td>
                    <td><?php echo $row['jam_selesai']; ?></td>
                    <td><?php echo $row['mata_pelajaran']; ?></td>
                    <td><?php echo $row['guru']; ?></td>
                    <td>
                        <a href="edit_jadwal.php?id=<?php echo $row['id']; ?>" class="btn">✏️ Edit</a>
                        <a href="kelola_jadwal.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-danger">🗑️ Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
