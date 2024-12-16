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

// Cek session
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

// Ambil data user
$user_query = $conn->query("SELECT * FROM users WHERE username = '" . $_SESSION['username'] . "'");
$user_data = $user_query->fetch_assoc();
if (!$user_data) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Pencarian peserta kelas
$kelas = $user_data['kelas'];
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$search_query = $search ? "AND nama LIKE '%$search%'" : '';
$peserta_query = $conn->query("SELECT nama, gender, email FROM siswa WHERE kelas = '$kelas' $search_query");

// Deteksi apakah halaman sedang menggunakan pencarian
$is_searching = !empty($search);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peserta Kelas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: url('th.jpg') no-repeat center center fixed;
            background-size: cover;
            <?php if (!$is_searching): ?> /* Animasi hanya jika bukan pencarian */
            opacity: 0;
            animation: fadeIn 1.5s forwards;
            <?php endif; ?>
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: -1;
        }

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

        .header {
            background: linear-gradient(to right, #6b8e65, #4e6f47);
            position: fixed;
            top: 0;
            left: 260px;
            width: calc(100% - 260px);
            padding: 15px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .main-content {
            width: 70%;
            margin-left: 330px;
            margin-top: 80px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #4e6f47;
            margin-bottom: 20px;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            width: 50%;
            border-radius: 8px;
            border: 1px solid #ccc;
            transition: box-shadow 0.3s ease;
        }

        button {
            padding: 10px 15px;
            background: #6b8e65;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #6b8e65;
            color: white;
        }

        p.message {
            text-align: center;
            color: #4e6f47;
            font-weight: bold;
        }

    </style>
</head>
<body>
    <div class="overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Menu</h3>
        <a href="dashboard_user.php">🏠 Dashboard</a>
        <a href="jadwal.php">📅 Jadwal</a>
        <a href="tugas.php">📝 Tugas</a>
        <a href="peserta.php">👥 Peserta Kelas</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <!-- Header -->
    <div class="header">
        <h1>Peserta Kelas</h1>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Peserta Kelas - <?php echo htmlspecialchars($user_data['kelas']); ?></h2>

        <!-- Form Pencarian -->
        <form method="GET">
            <input type="text" name="search" placeholder="Cari Nama Peserta..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">🔍 Cari</button>
        </form>

        <?php if ($is_searching): ?>
            <p class="message">Hasil pencarian untuk: "<?php echo htmlspecialchars($search); ?>"</p>
        <?php endif; ?>

        <?php if ($peserta_query->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Gender</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = $peserta_query->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="message">Peserta tidak ditemukan. Coba kata kunci lain.</p>
        <?php endif; ?>
    </div>
</body>
</html>
