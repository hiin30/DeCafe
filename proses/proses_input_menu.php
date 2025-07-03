<?php
include "connect.php";
session_start();

// Atur timezone ke Jakarta
date_default_timezone_set('Asia/Jakarta');

$nama_menu = (isset($_POST['nama_menu'])) ? htmlentities($_POST['nama_menu']) : "";
$keterangan = (isset($_POST['keterangan'])) ? htmlentities($_POST['keterangan']) : "";
$kat_menu = (isset($_POST['kat_menu'])) ? htmlentities($_POST['kat_menu']) : "";
$harga = (isset($_POST['harga'])) ? htmlentities($_POST['harga']) : "";
$stock = (isset($_POST['stock'])) ? htmlentities($_POST['stock']) : "";
$companycode = isset($_SESSION['companycode_decafe']) ? $_SESSION['companycode_decafe'] : 'decafe';
$status = 1;
$isdeleted = 0;
$createdby = $_SESSION['username_decafe']; // ambil dari session
$createddate = date('Y-m-d H:i:s'); // buat waktu sekarang
$lastupdatedby = $_SESSION['username_decafe'];
$lastupdateddate = date('Y-m-d H:i:s');

// Persiapan upload file
$kode_rand = rand(10000, 99999) . "-";
$target_dir = "../assets/img/" . $kode_rand;
$target_file = $target_dir . basename($_FILES['foto']['name']);
$imageType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

if (!empty($_POST['input_menu_validate'])) {
    // Cek apakah file yang diupload adalah gambar
    $cek = getimagesize($_FILES['foto']['tmp_name']);
    if ($cek === false) {
        $message = "Ini bukan file gambar";
        $statusUpload = 0;
    } else {
        $statusUpload = 1;
        if (file_exists($target_file)) {
            $message = "Maaf, File yang dimasukkan telah ada";
            $statusUpload = 0;
        } else {
            if ($_FILES['foto']['size'] > 500000) { // 500 KB
                $message = "File foto yang diupload terlalu besar";
                $statusUpload = 0;
            } else {
                if ($imageType != "jpg" && $imageType != "jpeg" && $imageType != "png" && $imageType != "gif") {
                    $message = "Maaf, hanya file JPG, JPEG, PNG, dan GIF yang diperbolehkan";
                    $statusUpload = 0;
                }
            }
        }
    }

    if ($statusUpload == 0) {
        $message = '<script>alert("' . $message . ', Gambar tidak dapat diupload"); 
                    window.location="../menu"</script>';
    } else {
        // Cek apakah nama menu sudah ada
        $select = mysqli_query($conn, "SELECT * FROM tb_daftar_menu WHERE nama_menu = '$nama_menu'");
        if (mysqli_num_rows($select) > 0) {
            $message = '<script>alert("Nama menu yang dimasukkan telah ada"); 
                    window.location="../menu";</script>';
        } else {
            // Upload file
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                // INSERT ke database dengan tambahan field baru
                $query = mysqli_query($conn, "INSERT INTO tb_daftar_menu (foto, nama_menu, keterangan, kategori, harga, stock, companycode, status, isdeleted, createdby, createddate, lastupdatedby, lastupdateddate)
                VALUES (
                    '" . $kode_rand . $_FILES['foto']['name'] . "',
                    '$nama_menu',
                    '$keterangan',
                    '$kat_menu',
                    '$harga',
                    '$stock',
                    '$companycode',
                    '$status',
                    '$isdeleted',
                    '$createdby',
                    '$createddate',
                    '$lastupdatedby',
                    '$lastupdateddate'
                )");

                if ($query) {
                    $message = '<script>alert("Data berhasil dimasukkan"); 
                        window.location="../menu"</script>';
                } else {
                    $message = '<script>alert("Data gagal dimasukkan: ' . mysqli_error($conn) . '"); 
                        window.location="../menu"</script>';
                }
            } else {
                $message = '<script>alert("Maaf, Terjadi kesalahan. File tidak dapat diupload"); 
                    window.location="../menu"</script>';
            }
        }
    }
}
echo $message;
?>