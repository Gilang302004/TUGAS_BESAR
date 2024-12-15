<?php
session_start();
include 'db.php';

$success = '';
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Sebaiknya gunakan bcrypt di produksi
    $role = 'user'; // Default role 'user'

    // Cek apakah username sudah ada
    $check = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($check->num_rows > 0) {
        $error = "Username sudah digunakan!";
    } else {
        // Tambahkan akun ke database
        $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
        if ($conn->query($sql) === TRUE) {
            $success = "Akun berhasil dibuat. <a href='index.php'></a>";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Daftar Akun</title>
    <style>
        /* Reset Default Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body & Background */
body, html {
    height: 100%;
    font-family: 'Poppins', Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    background: url('th.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #333;
}

/* Overlay Effect */
.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(3px);
    z-index: 0;
}

/* Container Style */
.container {
    position: relative;
    background: linear-gradient(to bottom, #ffffff, #f0f4f3);
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    width: 100%;
    max-width: 400px;
    z-index: 1;
    animation: fadeIn 0.8s ease-in-out;
}

h2 {
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
    color: #4e6f47;
    letter-spacing: 1px;
    font-weight: 600;
}

/* Tombol Kembali (Atas Kiri) */
.btn-back {
    position: absolute;
    top: 20px;
    left: 20px;
    color: #4e6f47;
    font-size: 24px;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease, transform 0.2s ease;
}

.btn-back:hover {
    color: #3b5a38;
    transform: scale(1.2);
}



/* Form Styles */
form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #555;
}

input {
    padding: 12px;
    margin-bottom: 20px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    background: #f9f9f9;
    color: #333;
    transition: border 0.3s ease, box-shadow 0.3s ease;
}

input:focus {
    border-color: #6b8e65;
    box-shadow: 0 0 8px rgba(107, 142, 101, 0.5);
    outline: none;
}

/* Button Styles */
button {
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    color: #fff;
    background: linear-gradient(to right, #6b8e65, #4e6f47);
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s;
}

button:hover {
    background: linear-gradient(to right, #4e6f47, #3b5a38);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Message Styles */
p.error, p.success {
    text-align: center;
    font-size: 14px;
    font-weight: bold;
}

p.error {
    color: #ff4d4d;
}

p.success {
    color: #28a745;
}

/* Link Style */
.link {
    text-align: center;
    margin-top: 10px;
    font-size: 14px;
}

.link a {
    color: #4e6f47;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
}

.link a:hover {
    color: #3b5a38;
}

/* Fade-in Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Styling */
@media (max-width: 480px) {
    .container {
        padding: 20px;
    }

    h2 {
        font-size: 20px;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <!-- Tombol Kembali -->
<a href="javascript:history.back()" class="btn-back" title="Kembali">←</a>

        <h2>Daftar Akun</h2>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Masukkan Username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Masukkan Password" required>

            <button type="submit">Daftar</button>
        </form>
        <p class="error"><?php echo $error; ?></p>
        <p class="success"><?php echo $success; ?></p>

        <p style="text-align: center; margin-top: 10px;">
            Sudah punya akun? <a href="index.php" style="color: #4e6f47; text-decoration: none; font-weight: bold;">Login di sini</a>
        </p>
    </div>
</body>
</html>
