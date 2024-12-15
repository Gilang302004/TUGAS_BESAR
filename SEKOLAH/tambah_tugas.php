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

$success_message = ''; // Inisialisasi pesan sukses

// Proses Tambah Tugas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $deadline = $_POST['deadline'];
    $guru = $_POST['guru']; // Ambil nama guru dari input form
    $kelas = $_POST['kelas']; // Ambil kelas dari input form
    $file = ''; // Default kosong jika tidak ada file diunggah

    // Proses Upload File
    if (!empty($_FILES['file']['name']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_name = basename($_FILES['file']['name']);
        $file_target = "uploads/" . $file_name;

        if (move_uploaded_file($file_tmp, $file_target)) {
            $file = $file_name;
        } else {
            echo "Gagal mengunggah file.";
            exit();
        }
    }

    // Simpan data ke database
    $sql = "INSERT INTO tugas (judul, deskripsi, deadline, file, guru, kelas) 
            VALUES ('$judul', '$deskripsi', '$deadline', '$file', '$guru', '$kelas')";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Tugas berhasil ditambahkan oleh $guru!";
    } else {
        $success_message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tambah Tugas</title>
    <style>
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
            position: fixed; /* Tetap di tempat saat scroll */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4); /* Warna overlay gelap */
            backdrop-filter: blur(10px); /* Efek blur */
            -webkit-backdrop-filter: blur(10px); /* Untuk browser Webkit */
            z-index: 0; /* Tetap di belakang kontainer */
        }

        /* Container */
        .container {
            position: relative;
            z-index: 1; /* Kontainer di atas overlay */
            max-width: 500px;
            margin: 60px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.9); /* Transparansi kontainer */
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
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

        .success-message {
            text-align: center;
            margin-top: 10px;
            padding: 10px;
            color: #28a745;
            font-weight: bold;
            border: 1px solid #28a745;
            border-radius: 8px;
            background-color: #e9f9ec;
            display: <?php echo ($success_message != '') ? 'block' : 'none'; ?>;
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

        input, textarea {
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        input:focus, textarea:focus {
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

    <!-- Header -->
    <div class="container">
        <h1>Tambah Tugas</h1>
        <a href="kelola_tugas.php" style="color: #4e6f47; text-decoration: none; font-weight: bold;">← Kembali ke Kelola Tugas</a><br><br>

        <!-- Pesan Sukses -->
        <?php if ($success_message): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <!-- Form Tambah Tugas -->
        <form method="POST" action="" enctype="multipart/form-data">
            <label for="judul">Judul Tugas:</label>
            <input type="text" id="judul" name="judul" required>

            <label for="deskripsi">Deskripsi:</label>
            <textarea id="deskripsi" name="deskripsi" rows="5" required></textarea>

            <label for="deadline">Deadline:</label>
            <input type="date" id="deadline" name="deadline" required>

            <label for="guru">Nama Guru:</label>
            <input type="text" id="guru" name="guru" placeholder="Masukkan Nama Guru" required>

            <!-- Kolom Kelas -->
            <label for="kelas">Kelas:</label>
            <input type="text" id="kelas" name="kelas" placeholder="Masukkan Kelas" required>

            <label for="file">Upload File (Opsional):</label>
            <input type="file" id="file" name="file" accept=".pdf, .docx, .txt">

            <button type="submit">Simpan</button>
        </form>
    </div>
</body>
</html>
