<?php
include "connect.php";
session_start(); // Pastikan sesi diaktifkan
date_default_timezone_set('Asia/Jakarta');

// Ambil input dari form
$id = isset($_POST['id']) ? htmlentities($_POST['id']) : "";
$jenismenu = isset($_POST['jenismenu']) ? htmlentities($_POST['jenismenu']) : "";
$katmenu = isset($_POST['katmenu']) ? htmlentities($_POST['katmenu']) : "";

// Data tambahan
$lastupdatedby = isset($_SESSION['username_decafe']) ? $_SESSION['username_decafe'] : "Unknown User";
$lastupdateddate = date('Y-m-d H:i:s');

// Inisialisasi pesan
$message = "";

// Validasi input tidak boleh kosong
if (!empty($_POST['input_katmenu_validate'])) {
    if (empty($id) || empty($jenismenu) || empty($katmenu)) {
        $message = '<script>alert("Input tidak boleh kosong."); window.location="../katmenu";</script>';
        echo $message;
        exit;
    }

    // Periksa apakah kategori menu sudah ada kecuali untuk ID saat ini
    $select = mysqli_query($conn, "SELECT kategori_menu FROM tb_kategori_menu WHERE kategori_menu = '$katmenu' AND id_kat_menu != '$id'");
    if (!$select) {
        $message = '<script>alert("Terjadi kesalahan pada query: ' . mysqli_error($conn) . '"); window.location="../katmenu";</script>';
        echo $message;
        exit;
    }

    if (mysqli_num_rows($select) > 0) {
        $message = '<script>alert("Kategori menu yang dimasukkan telah ada."); window.location="../katmenu";</script>';
    } else {
        // Query update
        $query = mysqli_query($conn, "UPDATE tb_kategori_menu SET 
            jenis_menu='$jenismenu', 
            kategori_menu='$katmenu', 
            lastupdatedby='$lastupdatedby', 
            lastupdateddate='$lastupdateddate' 
            WHERE id_kat_menu='$id'");

        if ($query) {
            $message = '<script>alert("Data berhasil diupdate."); window.location="../katmenu";</script>';
        } else {
            $message = '<script>alert("Data gagal diupdate: ' . mysqli_error($conn) . '"); window.location="../katmenu";</script>';
        }
    }
}

// Tampilkan pesan dan hentikan eksekusi
echo $message;
exit;
?>
