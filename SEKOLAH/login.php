<?php
session_start();
include 'db.php';

$error = '';

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

// Perbarui waktu terakhir aktivitas
$_SESSION['LAST_ACTIVITY'] = time();

// Cek cookie saat halaman dimuat
$cookie_username = isset($_COOKIE['remember_username']) ? $_COOKIE['remember_username'] : '';
$cookie_password = isset($_COOKIE['remember_password']) ? $_COOKIE['remember_password'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Gunakan MD5 (sebaiknya bcrypt untuk produksi)
    $remember = isset($_POST['remember']); // Checkbox Remember Me

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        // Set Cookie jika Remember Me dicentang
        if ($remember) {
            setcookie('remember_username', $username, time() + (86400 * 30), "/"); // 30 hari
            setcookie('remember_password', $_POST['password'], time() + (86400 * 30), "/");
        } else {
            // Hapus cookie jika Remember Me tidak dicentang
            setcookie('remember_username', '', time() - 3600, "/");
            setcookie('remember_password', '', time() - 3600, "/");
        }

        // Redirect berdasarkan role
        if ($row['role'] == 'admin') {
            header("Location: dashboard_admin.php");
        } else {
            header("Location: dashboard_user.php");
        }
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Sekolah</title>
    <script>
        let idleTime = 0;

        // Event listeners untuk reset timer
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;
        document.onclick = resetTimer;

        function resetTimer() {
            idleTime = 0; // Reset waktu idle
        }

        setInterval(function() {
            idleTime++;
            if (idleTime >= 5) { // Jika idle 5 menit
                location.reload();
            }
        }, 60000); // Cek setiap 1 menit
    </script>
    <style>
        /* General Styles */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Poppins', Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
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
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(5px);
            z-index: 0;
        }

        /* Header */
        .header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            text-align: center;
            padding: 20px 0;
            background: rgba(120, 180, 110, 0.8);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            z-index: 2;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* Login Container */
        .container {
            position: relative;
            z-index: 1;
            background: rgba(245, 245, 245, 0.9);
            border-radius: 12px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
            color: #5a6f50;
        }

        /* Form */
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
            margin-bottom: 15px;
            border: 2px solid #ccc;
            border-radius: 8px;
            background: #fafafa;
            font-size: 14px;
        }

        input:focus {
            border-color: #8ca37c;
            outline: none;
        }

        /* Remember Me */
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .remember-me input {
            margin-right: 8px;
        }

        /* Button */
        button {
            padding: 10px;
            background: linear-gradient(to right, #8ca37c, #6b8e65);
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s;
        }

        button:hover {
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            transform: scale(1.05);
        }

        /* Error Message */
        p.error {
            text-align: center;
            color: #ff4d4d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <!-- Header -->
    <div class="header">
        <h1>Selamat Datang di Sistem Informasi Sekolah</h1>
    </div>

    <!-- Login Container -->
    <div class="container">
        <h2>Login Sekolah</h2>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Masukkan Username" value="<?php echo htmlspecialchars($cookie_username); ?>" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Masukkan Password" value="<?php echo htmlspecialchars($cookie_password); ?>" required>

            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember" <?php echo ($cookie_username && $cookie_password) ? 'checked' : ''; ?>>
                <label for="remember">Remember Me</label>
            </div>

            <button type="submit">Login</button>
        </form>

        <!-- Tautan untuk Daftar sebagai User Baru -->
        <p style="text-align: center; margin-top: 10px;">
            Belum punya akun? <a href="register_user.php" style="color: #4e6f47; text-decoration: none; font-weight: bold;">Daftar sebagai User Baru</a>
        </p>

        <!-- Tautan Lupa Username atau Password -->
        <p style="text-align: center; margin-top: 10px;">
            Lupa <a href="forgot_username_password.php" style="color: #4e6f47; text-decoration: none; font-weight: bold;">Username atau Password?</a>
        </p>

        <p class="error"><?php echo $error; ?></p>
    </div>
</body>
</html>
