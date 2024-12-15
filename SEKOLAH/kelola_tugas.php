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

// Hapus tugas jika ada ID yang dikirim
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM tugas WHERE id=$id");
    header("Location: kelola_tugas.php");
    exit();
}

// Tentukan durasi sesi dalam detik (misalnya 5 menit = 300 detik)
$timeout_duration = 1 * 60; // 5 menit

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

// Hapus tugas jika ada ID yang dikirim
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM tugas WHERE id=$id");
    header("Location: kelola_tugas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Tugas</title>
    <style>
        .action-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px; /* Jarak antar tombol */
        }

        .action {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #6b8e65;
            font-weight: bold;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 6px;
            transition: background 0.3s ease, transform 0.2s;
        }

        .action:hover {
            background: #e8f5e9;
            color: #3c4e39;
            transform: scale(1.05);
        }

        .action-danger {
            color: #dc3545;
        }

        .action-danger:hover {
            background: #f8d7da;
            color: #b71c1c;
        }

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

        /* Action Buttons */
        .btn-container {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
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

        /* Table */
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
        }

        table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table tr:hover {
            background: #f1f8e9;
            transition: background 0.3s;
        }

        /* Edit and Delete Links */
        a.action {
            color: #6b8e65;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a.action:hover {
            color: #3c4e39;
        }

        a.action-danger {
            color: #dc3545;
        }

        a.action-danger:hover {
            color: #b71c1c;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <!-- Header -->
    <div class="header">
        <h1>Kelola Tugas</h1>
    </div>

    <!-- Container -->
    <div class="container">
        <!-- Action Buttons -->
        <div class="btn-container">
            <a href="dashboard_admin.php" class="btn">← Kembali ke Dashboard</a>
            <a href="tambah_tugas.php" class="btn">+ Tambah Tugas</a>
        </div>

        <!-- Tabel Data Tugas -->
        <table>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Guru</th>
                <th>Tanggal Posting</th>
                <th>Deadline</th>
                <th>Kelas</th> <!-- New column for class -->
                <th>Aksi</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM tugas");
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['judul']); ?></td>
                <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                <td><?php echo htmlspecialchars($row['guru']); ?></td>
                <td><?php echo $row['tanggal_posting']; ?></td>
                <td><?php echo $row['deadline']; ?></td>
                <td><?php echo htmlspecialchars($row['kelas']); ?></td> <!-- Display class -->
                <td>
                    <div class="action-container">
                        <a href="edit_tugas.php?id=<?php echo $row['id']; ?>" class="action">
                            ✏️ Edit
                        </a>
                        <a href="kelola_tugas.php?delete=<?php echo $row['id']; ?>" 
                        onclick="return confirm('Yakin ingin menghapus?')" class="action action-danger">
                            🗑️ Hapus
                        </a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
