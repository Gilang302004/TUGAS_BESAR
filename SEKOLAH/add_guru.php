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

// Cek apakah user memiliki hak akses
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Inisialisasi variabel pesan
$success = '';

// Proses penambahan data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $mata_pelajaran = $_POST['mata_pelajaran'];
    $umur = $_POST['umur'];

    // Query INSERT dengan validasi sukses atau error
    if ($conn->query("INSERT INTO guru (nama, mata_pelajaran, umur) VALUES ('$nama', '$mata_pelajaran', $umur)")) {
        $success = "Data guru berhasil ditambahkan!";
    } else {
        $success = "Gagal menambahkan data guru: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tambah Guru</title>
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
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(6px);
            z-index: 0;
        }

        /* Container */
        .container {
            position: relative;
            max-width: 500px;
            margin: 0px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            z-index: 1;
            animation: fadeInUp 1s ease-in-out;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: bold;
            color: #4e6f47;
        }

        a {
            display: inline-block;
            margin-bottom: 20px;
            color: #4e6f47;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        a:hover {
            color: #6b8e65;
        }

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
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        input:focus {
            border-color: #6b8e65;
            outline: none;
            box-shadow: 0 0 6px rgba(107, 142, 101, 0.5);
        }

        button {
            padding: 12px;
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
        }

        button:hover {
            background: linear-gradient(to right, #4e6f47, #3e5838);
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .success-message {
            text-align: center;
            margin-top: 10px;
            padding: 10px;
            color: #28a745;
            font-weight: bold;
            border: 1px solid #28a745;
            border-radius: 8px;
            background-color: #e9f9ec;
            display: <?php echo ($success != '') ? 'block' : 'none'; ?>;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <h1>Tambah Data Guru</h1>
        <a href="kelola_guru.php">← Kembali ke Kelola Guru</a>
        
        <!-- Pesan sukses -->
        <div class="success-message">
            <?php echo $success; ?>
        </div>

        <form method="POST" action="">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Guru" required>

            <label for="mata_pelajaran">Mata Pelajaran:</label>
            <input type="text" id="mata_pelajaran" name="mata_pelajaran" placeholder="Masukkan Mata Pelajaran" required>

            <label for="umur">Umur:</label>
            <input type="number" id="umur" name="umur" placeholder="Masukkan Umur" required>

            <button type="submit">Tambah</button>
        </form>
    </div>
</body>
</html>
