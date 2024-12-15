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

// Inisialisasi pesan
$success_message = "";

// Ambil ID berikutnya (opsional)
$result = $conn->query("SELECT MAX(id) AS max_id FROM jadwal");
$row = $result->fetch_assoc();
$next_id = $row['max_id'] + 1;

// Proses penambahan data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hari = $_POST['hari'];
    $kelas = $_POST['kelas'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $mata_pelajaran = $_POST['mata_pelajaran'];
    $guru = $_POST['guru'];

    $sql = "INSERT INTO jadwal (hari, kelas, jam_mulai, jam_selesai, mata_pelajaran, guru) 
            VALUES ('$hari', '$kelas', '$jam_mulai', '$jam_selesai', '$mata_pelajaran', '$guru')";
    
    if ($conn->query($sql) === TRUE) {
        $success_message = "Jadwal berhasil ditambahkan!";
    } else {
        $success_message = "Gagal menambahkan jadwal: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tambah Jadwal</title>
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

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #4e6f47;
            font-size: 28px;
        }

        a {
            display: inline-block;
            margin-bottom: 20px;
            color: #4e6f47;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #2e4d27;
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
            animation: fadeInUp 0.8s ease-in-out;
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

        input, select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus, select:focus {
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
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <h1>Tambah Jadwal Pelajaran</h1>
        <a href="kelola_jadwal.php">← Kembali ke Kelola Jadwal</a>

        <!-- Pesan Sukses -->
        <?php if ($success_message): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <!-- Form Input -->
        <form method="POST" action="">
            <label for="hari">Hari:</label>
            <input type="text" id="hari" name="hari" required>

            <label for="kelas">Kelas:</label>
            <select id="kelas" name="kelas" required>
                 <!-- Kelas X IPA 1-5 -->
    <option value="X IPA 1">Kelas X IPA 1</option>
    <option value="X IPA 2">Kelas X IPA 2</option>
    <option value="X IPA 3">Kelas X IPA 3</option>
    <option value="X IPA 4">Kelas X IPA 4</option>
    <option value="X IPA 5">Kelas X IPA 5</option>

    <!-- Kelas XI IPA 1-5 -->
    <option value="XI IPA 1">Kelas XI IPA 1</option>
    <option value="XI IPA 2">Kelas XI IPA 2</option>
    <option value="XI IPA 3">Kelas XI IPA 3</option>
    <option value="XI IPA 4">Kelas XI IPA 4</option>
    <option value="XI IPA 5">Kelas XI IPA 5</option>

    <!-- Kelas XII IPA 1-5 -->
    <option value="XII IPA 1">Kelas XII IPA 1</option>
    <option value="XII IPA 2">Kelas XII IPA 2</option>
    <option value="XII IPA 3">Kelas XII IPA 3</option>
    <option value="XII IPA 4">Kelas XII IPA 4</option>
    <option value="XII IPA 5">Kelas XII IPA 5</option>

    <!-- Kelas X IPS 1-5 -->
    <option value="X IPS 1">Kelas X IPS 1</option>
    <option value="X IPS 2">Kelas X IPS 2</option>
    <option value="X IPS 3">Kelas X IPS 3</option>
    <option value="X IPS 4">Kelas X IPS 4</option>
    <option value="X IPS 5">Kelas X IPS 5</option>

    <!-- Kelas XI IPS 1-5 -->
    <option value="XI IPS 1">Kelas XI IPS 1</option>
    <option value="XI IPS 2">Kelas XI IPS 2</option>
    <option value="XI IPS 3">Kelas XI IPS 3</option>
    <option value="XI IPS 4">Kelas XI IPS 4</option>
    <option value="XI IPS 5">Kelas XI IPS 5</option>

    <!-- Kelas XII IPS 1-5 -->
    <option value="XII IPS 1">Kelas XII IPS 1</option>
    <option value="XII IPS 2">Kelas XII IPS 2</option>
    <option value="XII IPS 3">Kelas XII IPS 3</option>
    <option value="XII IPS 4">Kelas XII IPS 4</option>
    <option value="XII IPS 5">Kelas XII IPS 5</option>
            </select>

            <label for="jam_mulai">Jam Mulai:</label>
            <input type="time" id="jam_mulai" name="jam_mulai" required>

            <label for="jam_selesai">Jam Selesai:</label>
            <input type="time" id="jam_selesai" name="jam_selesai" required>

            <label for="mata_pelajaran">Mata Pelajaran:</label>
            <input type="text" id="mata_pelajaran" name="mata_pelajaran" required>

            <label for="guru">Guru:</label>
            <input type="text" id="guru" name="guru" required>

            <button type="submit">Tambah Jadwal</button>
        </form>
    </div>
</body>
</html>
