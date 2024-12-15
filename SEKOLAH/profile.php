<?php
session_start();
include 'db.php';

// Timeout Session
$timeout_duration = 120;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: index.php?timeout=true");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

// Periksa koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Ambil data profil user
$username = mysqli_real_escape_string($conn, $_SESSION['username']);

// CRUD: Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $new_name = mysqli_real_escape_string($conn, $_POST['nama']);
    $new_kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);

    $update_query = "UPDATE siswa SET nama='$new_name', kelas='$new_kelas', email='$new_email' WHERE nama='$username'";
    if ($conn->query($update_query)) {
        $_SESSION['username'] = $new_name; // Update session jika nama diubah
        $success_message = "Profil berhasil diperbarui!";
    } else {
        $error_message = "Gagal memperbarui profil: " . $conn->error;
    }
}

// CRUD: Handle Delete
if (isset($_POST['delete'])) {
    $delete_query = "DELETE FROM siswa WHERE nama='$username'";
    if ($conn->query($delete_query)) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Gagal menghapus akun: " . $conn->error;
    }
}

// Ambil data terbaru
$user_query = $conn->query("SELECT nama, kelas, email FROM siswa WHERE nama = '$username'");
$user = $user_query->fetch_assoc();
if (!$user) {
    die("Data pengguna tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Profil Pengguna</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { display: flex; min-height: 100vh; background: url('th.jpg') no-repeat center center fixed; background-size: cover; animation: fadeIn 1s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .sidebar {
            width: 260px;
            background: linear-gradient(to bottom right, #cdeac0, #6b8e65);
            height: 100%;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            animation: fadeIn 1s ease-out;
        }

        .sidebar h3 {
            text-align: center;
            color: #4e6f47;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            padding: 12px;
            margin: 10px 0;
            text-decoration: none;
            color: #555;
            background: #e8f5e9;
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            color: #fff;
        }
        .header { background: linear-gradient(to right, #6b8e65, #4e6f47); color: white; padding: 15px; width: calc(100% - 260px); position: fixed; left: 260px; display: flex; justify-content: center; align-items: center; }
        .main-content { margin-left: 260px; margin-top: 80px; padding: 30px; animation: fadeIn 1s ease-out; flex: 1; display: flex; justify-content: center; align-items: center; }
        .profile-card { width: 600px; background: rgba(255, 255, 255, 0.95); border-radius: 12px; padding: 30px; text-align: center; }
        .profile-card input, .profile-card button { margin: 10px; padding: 10px; width: 100%; border-radius: 8px; border: 1px solid #ccc; }
        button { background: linear-gradient(to right, #6b8e65, #4e6f47); color: white; border: none; cursor: pointer; transition: 0.3s; }
        button:hover { background: #4e6f47; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>Menu User</h3>
        <a href="dashboard_user.php">🏠 Dashboard</a>
        <a href="jadwal.php">📅 Jadwal</a>
        <a href="tugas.php">📝 Tugas</a>
        <a href="pengumuman.php">📢 Pengumuman</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="header">
        <h1>Profil Pengguna</h1>
    </div>

    <div class="main-content">
        <div class="profile-card">
            <h2>Profil Anda</h2>
            <?php if (isset($success_message)) echo "<p style='color:green;'>$success_message</p>"; ?>
            <?php if (isset($error_message)) echo "<p style='color:red;'>$error_message</p>"; ?>
            <form method="POST" action="">
                <input type="text" name="nama" placeholder="Nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                <input type="text" name="kelas" placeholder="Kelas" value="<?php echo htmlspecialchars($user['kelas']); ?>" required>
                <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                <button type="submit" name="update">Perbarui Profil</button>
                <button type="submit" name="delete" onclick="return confirm('Yakin ingin menghapus akun Anda?');">Hapus Akun</button>
            </form>
        </div>
    </div>
</body>
</html>
