<?php
session_start();
include 'db.php';

// Tentukan durasi sesi dalam detik (misalnya 5 menit = 300 detik)
$timeout_duration = 2 * 60; // 5 menit

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

// Hapus data siswa jika ada ID yang dikirim
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM siswa WHERE id=$id");
    header("Location: kelola_siswa.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Siswa</title>
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

        .header {
            background: rgba(255, 255, 255, 0.85);
            color: #4e6f47;
            text-align: center;
            padding: 20px;
            border-bottom: 4px solid #6b8e65;
        }

        .content {
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            max-width: 90%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th {
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            color: #fff;
            padding: 15px;
        }

        table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table tr:hover {
            background: #f1f8e9;
        }

        .btn {
            padding: 10px 15px;
            text-decoration: none;
            color: #fff;
            background: #6b8e65;
            border-radius: 8px;
            transition: background 0.3s, transform 0.3s;
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
    <!-- Header -->
    <div class="header">
        <h1>Kelola Data Siswa</h1>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2>Daftar Siswa</h2>
        <div style="margin-bottom: 20px;">
            <a href="dashboard_admin.php" class="btn">← Kembali ke Dashboard</a>
            <a href="add_siswa.php" class="btn">+ Tambah Siswa</a>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Umur</th>
                <th>Gender</th> <!-- Kolom baru -->
                <th>Email</th> <!-- Kolom baru -->
                <th>Aksi</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM siswa");
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['kelas']; ?></td>
                <td><?php echo $row['umur']; ?></td>
                <td><?php echo $row['gender']; ?></td> <!-- Menampilkan gender -->
                <td><?php echo $row['email']; ?></td> <!-- Menampilkan email -->
                <td>
                    <a href="edit_siswa.php?id=<?php echo $row['id']; ?>" class="btn">✏️ Edit</a>
                    <a href="kelola_siswa.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-danger">🗑️ Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
