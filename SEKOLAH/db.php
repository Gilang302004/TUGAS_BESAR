<?php
$host = "localhost";
$user = "root"; // Ubah sesuai user database Anda
$pass = "";     // Ubah sesuai password database Anda
$dbname = "db_sekolah";

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
?>

