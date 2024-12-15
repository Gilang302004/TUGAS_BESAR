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
    $username = $_SESSION['username'];
    $password_lama = md5($_POST['password_lama']); // Hash password lama dengan md5
    $password_baru = md5($_POST['password_baru']); // Hash password baru dengan md5

    // Cek password lama di database
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password_lama'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        // Jika password lama cocok, update dengan password baru
        $update_query = "UPDATE users SET password='$password_baru' WHERE username='$username'";
        if ($conn->query($update_query) === TRUE) {
            echo "<script>alert('Kata sandi berhasil diubah!'); window.location.href = 'dashboard_admin.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat mengubah kata sandi.');</script>";
        }
    } else {
        echo "<script>alert('Password lama tidak sesuai!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ubah Kata Sandi</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #4e6f47;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #6b8e65;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #4e6f47;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #4e6f47;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ubah Kata Sandi</h1>
        <form method="POST" action="">
            <label>Password Lama:</label>
            <input type="password" name="password_lama" required>
            <label>Password Baru:</label>
            <input type="password" name="password_baru" required>
            <button type="submit">Simpan</button>
        </form>
        <a href="dashboard_admin.php">Kembali ke Dashboard</a>
    </div>
</body>
</html>
