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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_sekolah = $_POST['nama_sekolah'];
    $tahun_ajaran = $_POST['tahun_ajaran'];
    // Simpan pengaturan umum ke database (gunakan query UPDATE/INSERT sesuai kebutuhan)
    echo "Pengaturan umum berhasil disimpan!";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Pengaturan Umum</title>
</head>
<body>
    <h1>Pengaturan Umum</h1>
    <a href="dashboard_admin.php">Kembali</a><br><br>
    <form method="POST" action="">
        <label>Nama Sekolah:</label><br>
        <input type="text" name="nama_sekolah" required><br>
        <label>Tahun Ajaran:</label><br>
        <input type="text" name="tahun_ajaran" required><br><br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
