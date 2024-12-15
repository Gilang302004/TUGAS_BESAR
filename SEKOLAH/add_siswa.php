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
$error = '';

// Proses penambahan data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $umur = $_POST['umur'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];

    // Sanitasi dan validasi input
    $nama = mysqli_real_escape_string($conn, $nama);
    $kelas = mysqli_real_escape_string($conn, $kelas);
    $umur = intval($umur); // Pastikan umur adalah angka
    $gender = mysqli_real_escape_string($conn, $gender);
    $email = mysqli_real_escape_string($conn, $email);

    // Validasi input untuk memastikan data valid
    if (empty($nama) || empty($kelas) || empty($umur) || empty($gender) || empty($email) || $umur <= 0) {
        $error = "Semua data harus diisi dengan benar!";
    } else {
        // Tambahkan data siswa ke tabel siswa menggunakan prepared statement
        $stmt = $conn->prepare("INSERT INTO siswa (nama, kelas, umur, gender, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $nama, $kelas, $umur, $gender, $email);

        if ($stmt->execute()) {
            $success = "Data siswa berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan data siswa: " . $stmt->error;
        }

        // Tutup prepared statements
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tambah Siswa</title>
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
            color: #4e6f47;
        }

        a {
            display: inline-block;
            margin-bottom: 20px;
            color: #4e6f47;
            text-decoration: none;
            font-weight: bold;
        }

        form label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        form input, form select {
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            width: 100%;
        }

        form .gender {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        button {
            padding: 12px;
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        .success-message, .error-message {
            text-align: center;
            margin-top: 10px;
            padding: 10px;
            font-weight: bold;
            border-radius: 8px;
        }

        .success-message {
            color: #28a745;
            background-color: #e9f9ec;
        }

        .error-message {
            color: #dc3545;
            background-color: #f8d7da;
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
        <h1>Tambah Data Siswa</h1>
        <a href="kelola_siswa.php">← Kembali ke Kelola Siswa</a>
        
        <!-- Pesan sukses -->
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Pesan error -->
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Siswa" required>

            <label for="kelas">Kelas:</label>
            <input type="text" id="kelas" name="kelas" placeholder="Masukkan Kelas" required>

            <label for="umur">Umur:</label>
            <input type="number" id="umur" name="umur" placeholder="Masukkan Umur" required>

            <label>Gender:</label>
            <div class="gender">
                <label><input type="radio" name="gender" value="Laki-laki" required> Laki-laki</label>
                <label><input type="radio" name="gender" value="Perempuan"> Perempuan</label>
            </div>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Masukkan Email" required>

            <button type="submit">Tambah</button>
        </form>
    </div>
</body>
</html>
