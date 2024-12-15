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

// Ambil data guru berdasarkan ID
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM guru WHERE id=$id");
$guru = $result->fetch_assoc();

// Proses Update Data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $mata_pelajaran = $_POST['mata_pelajaran'];
    $umur = $_POST['umur'];

    // Query Update
    if ($conn->query("UPDATE guru SET nama='$nama', mata_pelajaran='$mata_pelajaran', umur=$umur WHERE id=$id")) {
        $_SESSION['success_message'] = "Data guru berhasil diperbarui!";
        header("Location: edit_guru.php?id=$id");
        exit();
    } else {
        $_SESSION['error_message'] = "Gagal memperbarui data: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Guru</title>
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
        }

        .header h1 {
            margin: 0;
            font-size: 30px;
            font-weight: bold;
        }

        /* Container */
        .container {
            margin: 50px auto;
            max-width: 500px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        /* Messages */
        .message {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .success {
            color: #28a745;
            background-color: #e9f9ec;
            border: 1px solid #28a745;
        }

        .error {
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #dc3545;
        }

        /* Back Button */
        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #4e6f47;
            font-weight: bold;
            transition: color 0.3s;
        }

        .btn-back:hover {
            color: #2e4d27;
        }

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: border 0.3s ease;
        }

        input:focus {
            border-color: #6b8e65;
            box-shadow: 0 0 5px rgba(107, 142, 101, 0.5);
            outline: none;
        }

        button {
            padding: 10px;
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover {
            background: #4e6f47;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <!-- Header -->
    <div class="header">
        <h1>Edit Data Guru</h1>
    </div>

    <!-- Container -->
    <div class="container">
        <a href="kelola_guru.php" class="btn-back">← Kembali ke Kelola Guru</a>

        <!-- Pesan Sukses atau Error -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="message success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
        <?php elseif (isset($_SESSION['error_message'])): ?>
            <div class="message error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>

        <!-- Form Edit Data -->
        <form method="POST" action="">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($guru['nama']); ?>" required>

            <label for="mata_pelajaran">Mata Pelajaran:</label>
            <input type="text" id="mata_pelajaran" name="mata_pelajaran" value="<?php echo htmlspecialchars($guru['mata_pelajaran']); ?>" required>

            <label for="umur">Umur:</label>
            <input type="number" id="umur" name="umur" value="<?php echo htmlspecialchars($guru['umur']); ?>" required>

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
