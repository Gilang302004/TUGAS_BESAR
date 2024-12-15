<?php
session_start();
include 'db.php';

// Tentukan durasi sesi dalam detik (misalnya 5 menit = 300 detik)
$timeout_duration = 2 * 60; // menit

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

// Inisialisasi pesan
$success_message = "";

// Ambil data tugas berdasarkan ID
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM tugas WHERE id=$id");
$tugas = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $deadline = $_POST['deadline'];
    $guru = $_POST['guru'];
    $kelas = $_POST['kelas'];  // Tambahkan kelas
    $file = $tugas['file']; // Default file lama

    // Proses Upload File (jika ada file baru)
    if (!empty($_FILES['file']['name']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_name = basename($_FILES['file']['name']);
        $file_target = "uploads/" . $file_name;

        if (move_uploaded_file($file_tmp, $file_target)) {
            $file = $file_name;
        } else {
            $success_message = "Gagal mengunggah file baru.";
        }
    }

    // Query update
    $sql = "UPDATE tugas SET judul='$judul', deskripsi='$deskripsi', deadline='$deadline', file='$file', guru='$guru', kelas='$kelas' WHERE id=$id";
    if ($conn->query($sql)) {
        $success_message = "Tugas berhasil diperbarui!";
    } else {
        $success_message = "Gagal memperbarui tugas: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Tugas</title>
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
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            z-index: -1;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.9);
            color: #4e6f47;
            text-align: center;
            padding: 20px;
            border-bottom: 4px solid #6b8e65;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }

        /* Container */
        .container {
            margin: 50px auto;
            max-width: 500px;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 1s ease-in-out;
        }

        /* Success Message */
        .success-message {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #e6ffed;
            border: 1px solid #28a745;
            color: #28a745;
            border-radius: 8px;
            font-weight: bold;
            animation: fadeIn 0.8s ease-in-out;
        }

        /* Back Button */
        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #4e6f47;
            font-weight: bold;
            transition: color 0.3s ease;
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

        input, textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus, textarea:focus {
            border-color: #6b8e65;
            box-shadow: 0 0 5px rgba(107, 142, 101, 0.5);
            outline: none;
        }

        button {
            padding: 10px;
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover {
            background: linear-gradient(to right, #4e6f47, #3c4e39);
            transform: scale(1.05);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <!-- Header -->
    <div class="header">
        <h1>Edit Tugas</h1>
    </div>

    <!-- Container -->
    <div class="container">
        <a href="kelola_tugas.php" class="btn-back">← Kembali ke Kelola Tugas</a>

        <!-- Pesan Sukses -->
        <?php if ($success_message): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <!-- Form Edit -->
        <form method="POST" action="" enctype="multipart/form-data">
            <label for="judul">Judul Tugas:</label>
            <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($tugas['judul']); ?>" required>

            <label for="deskripsi">Deskripsi:</label>
            <textarea id="deskripsi" name="deskripsi" rows="5" required><?php echo htmlspecialchars($tugas['deskripsi']); ?></textarea>

            <label for="deadline">Deadline:</label>
            <input type="date" id="deadline" name="deadline" value="<?php echo htmlspecialchars($tugas['deadline']); ?>" required>

            <label for="guru">Nama Guru:</label>
            <input type="text" id="guru" name="guru" value="<?php echo htmlspecialchars($tugas['guru']); ?>" required>

            <!-- Kolom Kelas -->
            <label for="kelas">Kelas:</label>
            <input type="text" id="kelas" name="kelas" value="<?php echo htmlspecialchars($tugas['kelas']); ?>" required>

            <label for="file">File Tugas (Opsional):</label>
            <input type="file" id="file" name="file" accept=".pdf, .docx, .txt">

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
