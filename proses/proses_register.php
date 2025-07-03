<?php
include "connect.php";
session_start();
date_default_timezone_set('Asia/Jakarta');

// Ambil data dari form
$username = isset($_POST['username']) ? htmlentities($_POST['username']) : "";
$nama = isset($_POST['nama']) ? htmlentities($_POST['nama']) : "";
$password = isset($_POST['password']) ? htmlentities($_POST['password']) : "";
$confirm_password = isset($_POST['confirm_password']) ? htmlentities($_POST['confirm_password']) : "";
$level = isset($_POST['level']) ? htmlentities($_POST['level']) : 5; // Default level Customer
$is_deleted = 0; // Nilai default is_deleted
$lastupdatedby = "manual regis"; // Penanda siapa yang melakukan pembaruan
$lastupdateddate = date('Y-m-d H:i:s'); // Waktu sekarang

// Validasi input
if ($username == "" || $nama == "" || $password == "" || $confirm_password == "") {
    echo '<script>alert("Semua field wajib diisi."); window.location="../register";</script>';
    exit;
}

// Validasi konfirmasi password
if ($password !== $confirm_password) {
    echo '<script>alert("Password dan konfirmasi password tidak sama."); window.location="../register";</script>';
    exit;
}

// Cek apakah username sudah terdaftar
$select = mysqli_query($conn, "SELECT * FROM tb_user WHERE username='$username'");
if (mysqli_num_rows($select) > 0) {
    echo '<script>alert("Email sudah digunakan. Silakan gunakan email lain."); window.location="../register";</script>';
    exit;
}

// Hash password
$hashed_password = md5($password);

// Query untuk menyimpan data ke database
$query = mysqli_query($conn, "
    INSERT INTO tb_user (username, nama, password, level, isdeleted, createdby, createddate) 
    VALUES ('$username', '$nama', '$hashed_password', '$level', '$is_deleted', '$lastupdatedby', '$lastupdateddate')
");

// Feedback untuk pengguna
if ($query) {
    echo '<script>alert("Registrasi berhasil. Silakan login."); window.location="../login";</script>';
} else {
    echo '<script>alert("Registrasi gagal: ' . mysqli_error($conn) . '"); window.location="../register";</script>';
}
?>
