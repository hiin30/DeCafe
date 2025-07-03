<?php
include "connect.php";
session_start();
date_default_timezone_set('Asia/Jakarta');
$id = isset($_POST['id']) ? htmlentities($_POST['id']) : "";
$nama_menu = isset($_POST['nama_menu']) ? htmlentities($_POST['nama_menu']) : "";
$keterangan = isset($_POST['keterangan']) ? htmlentities($_POST['keterangan']) : "";
$kat_menu = isset($_POST['kat_menu']) ? htmlentities($_POST['kat_menu']) : "";
$harga = isset($_POST['harga']) ? htmlentities($_POST['harga']) : "";
$stock = isset($_POST['stock']) ? htmlentities($_POST['stock']) : "";

// Last Updated By and Date
$lastupdatedby = isset($_SESSION['username_decafe']) ? $_SESSION['username_decafe'] : "Unknown User";
$lastupdateddate = date("Y-m-d H:i:s");

// Ambil foto dari database jika tidak ada upload baru
$current_foto_query = mysqli_query($conn, "SELECT foto FROM tb_daftar_menu WHERE id = '$id'");
$current_foto_row = mysqli_fetch_assoc($current_foto_query);
$current_foto = $current_foto_row['foto'];

$kode_rand = rand(10000, 99999) . "-";
$target_dir = "../assets/img/" . $kode_rand;
$target_file = $target_dir . basename($_FILES['foto']['name']);
$imageType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Cek apakah ada file baru yang diunggah
if (!empty($_FILES['foto']['name'])) {
    $statusUpload = 1;
    $cek = getimagesize($_FILES['foto']['tmp_name']);
    if ($cek === false) {
        $message = "Ini bukan file gambar";
        $statusUpload = 0;
    } else {
        if (file_exists($target_file)) {
            $message = "Maaf, File yang Dimasukkan Telah Ada";
            $statusUpload = 0;
        } elseif ($_FILES['foto']['size'] > 500000) { // 500 KB
            $message = "File foto yang diupload terlalu besar";
            $statusUpload = 0;
        } elseif ($imageType != "jpg" && $imageType != "png" && $imageType != "jpeg" && $imageType != "gif") {
            $message = "Maaf, hanya diperbolehkan gambar yang memiliki format JPG, JPEG, PNG, dan GIF";
            $statusUpload = 0;
        }
    }

    if ($statusUpload == 1) {
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            $foto_path = $kode_rand . $_FILES['foto']['name'];
        } else {
            $message = '<script>alert("Maaf, Terjadi Kesalahan File Tidak Dapat Diupload"); 
                        window.location="../menu";</script>';
            echo $message;
            exit;
        }
    } else {
        $message = '<script>alert("' . $message . ', Gambar tidak dapat diupload"); 
                    window.location="../menu";</script>';
        echo $message;
        exit;
    }
} else {
    // Gunakan foto lama jika tidak ada file baru yang diunggah
    $foto_path = $current_foto;
}

// Proses update ke database
$select = mysqli_query($conn, "SELECT * FROM tb_daftar_menu WHERE nama_menu = '$nama_menu' AND id != '$id'");
if (mysqli_num_rows($select) > 0) {
    $message = '<script>alert("Nama menu yang dimasukkan telah ada"); 
                window.location="../menu";</script>';
} else {
    $query = mysqli_query($conn, "UPDATE tb_daftar_menu SET 
        foto='$foto_path', 
        nama_menu='$nama_menu', 
        keterangan='$keterangan', 
        kategori='$kat_menu', 
        harga='$harga', 
        stock='$stock', 
        lastupdatedby='$lastupdatedby', 
        lastupdateddate='$lastupdateddate' 
        WHERE id='$id'");

    if ($query) {
        $message = '<script>alert("Data berhasil diupdate"); 
                    window.location="../menu";</script>';
    } else {
        $message = '<script>alert("Data gagal diupdate: ' . mysqli_error($conn) . '"); 
                    window.location="../menu";</script>';
    }
}
echo $message;
?>
