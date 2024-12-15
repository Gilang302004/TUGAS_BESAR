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

// Hapus data guru jika ada ID yang dikirim
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM guru WHERE id=$id");
    header("Location: kelola_guru.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Guru</title>
    <style>
        /* General Styles */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
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
            text-align: center;
            padding: 20px;
            color: #4e6f47;
            border-bottom: 4px solid #6b8e65;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeInDown 1s ease-in-out;
        }

        .header h1 {
            margin: 0;
            font-size: 30px;
            font-weight: bold;
        }

        /* Container */
        .container {
            margin: 40px auto;
            padding: 30px;
            max-width: 90%;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 1s ease-in-out;
        }

        .container h2{
            text-align: center;
            color: #4e6f47;
            margin-bottom: 20px;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 10px 15px;
            color: #fff;
            background: #6b8e65;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
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

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f9f9f9;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        table th {
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            color: #fff;
            padding: 15px;
            text-transform: uppercase;
        }

        table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table tr:hover {
            background-color: #f1f8e9;
        }

        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <!-- Header -->
    <div class="header">
        <h1>Kelola Data Guru</h1>
    </div>

    <!-- Container -->
    <div class="container">
    <h2>Daftar Guru</h2>
        <!-- Buttons -->
        <div style="display: flex; justify-content: flex-start; gap: 10px; margin-bottom: 20px;">
            <a href="dashboard_admin.php" class="btn">← Kembali ke Dashboard</a>
            <a href="add_guru.php" class="btn">+ Tambah Guru</a>
        </div>

        <!-- Table Data Guru -->
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Mata Pelajaran</th>
                <th>Umur</th>
                <th>Aksi</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM guru");
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['mata_pelajaran']; ?></td>
                <td><?php echo $row['umur']; ?></td>
                <td>
                    <a href="edit_guru.php?id=<?php echo $row['id']; ?>" class="btn">✏️ Edit</a>
                    <a href="kelola_guru.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-danger">🗑️ Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
