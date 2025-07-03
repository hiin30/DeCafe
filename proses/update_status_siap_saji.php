<?php
include "connect.php"; // pastikan path benar
session_start();

// Validasi input
if (isset($_GET['id_bayar']) && isset($_GET['status'])) {
    $id_bayar = htmlentities($_GET['id_bayar']);
    $status = (int)$_GET['status']; // Pastikan status adalah integer
    $updatedby = $_SESSION['username_decafe'] ?? 'system';
    $updateddate = date('Y-m-d H:i:s');

    // Update status berdasarkan nilai yang diterima
    $update = mysqli_query($conn, "UPDATE tb_bayar 
                                   SET status = '$status', 
                                       lastupdatedby = '$updatedby', 
                                       lastupdateddate = '$updateddate' 
                                   WHERE id_bayar = '$id_bayar'");

    if ($update) {
        echo "<script>alert('Status berhasil diperbarui'); window.location='../order';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status: " . mysqli_error($conn) . "'); window.location='../order';</script>";
    }
} else {
    echo "<script>alert('ID bayar atau status tidak ditemukan'); window.location='../order';</script>";
}
?>
