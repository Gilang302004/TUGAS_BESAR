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

// Simulasi Ubah Profil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    // Update data profil ke database (gunakan query UPDATE sesuai kebutuhan)
    echo "<script>alert('Profil berhasil diperbarui!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ubah Profil</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: url('th.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Overlay */
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

        /* Container */
        .container {
            position: relative;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            z-index: 1;
            animation: fadeIn 1s ease-in-out;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #4e6f47;
            font-size: 24px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #6b8e65;
            box-shadow: 0 0 8px rgba(107, 142, 101, 0.5);
            outline: none;
        }

        button {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background: linear-gradient(to right, #8ca37c, #6b8e65);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s;
        }

        button:hover {
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            transform: translateY(-2px);
        }

        a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #4e6f47;
            font-weight: bold;
            text-align: center;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #2e4834;
        }

        /* Fade-in Animation */
        @keyframes fadeIn {
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
        <h1>Ubah Profil</h1>
        <a href="dashboard_admin.php">← Kembali ke Dashboard</a><br><br><br>
        <form method="POST" action="">
            <label>Nama:</label>
            <input type="text" name="nama" value="<?php echo $_SESSION['username']; ?>" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <button type="submit">Simpan</button>
        </form>
    </div>
</body>
</html>
