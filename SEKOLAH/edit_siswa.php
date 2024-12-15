<?php
session_start();
include 'db.php';

// Tentukan durasi sesi dalam detik
$timeout_duration = 2 * 60;

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
    header("Location: index.php?timeout=true");
    exit();
}

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Ambil data siswa berdasarkan ID
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM siswa WHERE id=$id");
$siswa = $result->fetch_assoc();

// Proses Update Data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $umur = $_POST['umur'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];

    // Query Update
    if ($conn->query("UPDATE siswa SET nama='$nama', kelas='$kelas', umur=$umur, gender='$gender', email='$email' WHERE id=$id")) {
        $_SESSION['success_message'] = "Data siswa berhasil diperbarui!";
        header("Location: edit_siswa.php?id=$id");
        exit();
    } else {
        $_SESSION['error_message'] = "Gagal memperbarui data: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Siswa</title>
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
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: -1;
        }

        .header {
            background: rgba(255, 255, 255, 0.9);
            text-align: center;
            padding: 20px;
            color: #4e6f47;
            border-bottom: 4px solid #6b8e65;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .container {
            margin: 50px auto;
            max-width: 500px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

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

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input, select {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        button {
            padding: 10px;
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #4e6f47;
            font-weight: bold;
            transition: color 0.3s;
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
        <h1>Edit Data Siswa</h1>
    </div>

    <!-- Container -->
    <div class="container">
        <a href="kelola_siswa.php" class="btn-back">← Kembali ke Kelola Siswa</a>

        <!-- Pesan Sukses atau Error -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="message success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
        <?php elseif (isset($_SESSION['error_message'])): ?>
            <div class="message error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>

        <!-- Form Edit Data -->
        <form method="POST" action="">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($siswa['nama']); ?>" required>

            <label for="kelas">Kelas:</label>
            <input type="text" id="kelas" name="kelas" value="<?php echo htmlspecialchars($siswa['kelas']); ?>" required>

            <label for="umur">Umur:</label>
            <input type="number" id="umur" name="umur" value="<?php echo htmlspecialchars($siswa['umur']); ?>" required>

            <label>Gender:</label>
            <select name="gender" required>
                <option value="Laki-laki" <?php if ($siswa['gender'] == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                <option value="Perempuan" <?php if ($siswa['gender'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
            </select>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($siswa['email']); ?>" required>

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
