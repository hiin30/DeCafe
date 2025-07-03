<?php
include "connect.php";

if (isset($_GET['id_bayar']) && isset($_GET['status'])) {
    $id_bayar = htmlentities($_GET['id_bayar']);
    $status = (int)$_GET['status'];

    $update = mysqli_query($conn, "UPDATE tb_bayar 
                                   SET status = '$status' 
                                   WHERE id_bayar = '$id_bayar'");

    if ($update) {
        if ($status == 3) {
            echo "<script>alert('Order telah selesai.'); window.location='../dapur';</script>";
        } else {
            echo "<script>alert('Status berhasil diperbarui.'); window.location='../dapur';</script>";
        }
    } else {
        echo "<script>alert('Gagal memperbarui status: " . mysqli_error($conn) . "'); window.location='../dapur';</script>";
    }
} else {
    echo "<script>alert('Parameter tidak lengkap'); window.location='../dapur';</script>";
}
?>
