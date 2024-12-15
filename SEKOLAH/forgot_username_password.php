<?php
session_start();
include 'db.php';

$success = '';
$error = '';
$show_password_form = false;

// Tahap 1: Verifikasi Username
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['check_username'])) {
    $username = $_POST['username'];

    // Cek apakah username ada di database
    $query = "SELECT username FROM users WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $_SESSION['reset_username'] = $username; // Simpan username dalam sesi
        $success = "Username <strong>$username</strong> ditemukan. Silakan masukkan password baru.";
        $show_password_form = true;
    } else {
        $error = "Username tidak ditemukan. Pastikan username sudah benar.";
    }
}

// Tahap 2: Ubah Password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_password'])) {
    if (isset($_SESSION['reset_username'])) {
        $username = $_SESSION['reset_username'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password == $confirm_password) {
            $hashed_password = md5($new_password); // Hash password baru
            $update_query = "UPDATE users SET password='$hashed_password' WHERE username='$username'";
            if ($conn->query($update_query) === TRUE) {
                $success = "Password berhasil diubah. Silakan login kembali.";
                unset($_SESSION['reset_username']); // Hapus sesi reset
            } else {
                $error = "Gagal mengubah password. Silakan coba lagi.";
            }
        } else {
            $error = "Password baru dan konfirmasi password tidak cocok.";
        }
    } else {
        $error = "Sesi telah berakhir. Silakan masukkan username lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', Arial, sans-serif;
            background: url('th.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            color: #333;
        }

        .overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        h2 {
            text-align: center;
            color: #4e6f47;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #ccc;
            border-radius: 8px;
        }

        button {
            background: #6b8e65;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background: #4e6f47;
        }

        .message {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }

        .message.success { color: #28a745; }
        .message.error { color: #ff4d4d; }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <h2>Lupa Password</h2>

        <!-- Tahap 1: Verifikasi Username -->
        <?php if (!$show_password_form): ?>
            <form method="POST" action="">
                <label for="username">Masukkan Username Anda:</label>
                <input type="text" id="username" name="username" placeholder="Masukkan Username Anda" required>
                <button type="submit" name="check_username">Cek Username</button>
            </form>
        <?php endif; ?>

        <!-- Tahap 2: Form Ubah Password -->
        <?php if ($show_password_form): ?>
            <form method="POST" action="">
                <label for="new_password">Password Baru:</label>
                <input type="password" id="new_password" name="new_password" placeholder="Masukkan Password Baru" required>

                <label for="confirm_password">Konfirmasi Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Password Baru" required>

                <button type="submit" name="update_password">Ubah Password</button>
            </form>
        <?php endif; ?>

        <!-- Pesan Sukses atau Error -->
        <?php if ($success): ?>
            <p class="message success"><?php echo $success; ?></p>
        <?php elseif ($error): ?>
            <p class="message error"><?php echo $error; ?></p>
        <?php endif; ?>

        <a href="index.php" style="text-decoration: none; display: block; text-align: center; margin-top: 10px; color: #4e6f47; font-weight: bold;">Kembali ke Halaman Login</a>
    </div>
</body>
</html>
