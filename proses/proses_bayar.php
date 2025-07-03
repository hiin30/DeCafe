<?php
session_start();
include "connect.php";

// Atur timezone ke Jakarta
date_default_timezone_set('Asia/Jakarta');

$kode_order = (isset($_POST['kode_order'])) ? htmlentities($_POST['kode_order']) : "";
$meja = (isset($_POST['meja'])) ? htmlentities($_POST['meja']) : "";
$pelanggan = (isset($_POST['pelanggan'])) ? htmlentities($_POST['pelanggan']) : "";
$total = (isset($_POST['total'])) ? htmlentities($_POST['total']) : "";
$uang = (isset($_POST['uang'])) ? htmlentities($_POST['uang']) : "";
$kembalian = $uang - $total;
$companycode = isset($_SESSION['companycode_decafe']) ? $_SESSION['companycode_decafe'] : 'decafe';
$status = 1;
$isdeleted = 0;
$createdby = isset($_SESSION['username_decafe']) ? $_SESSION['username_decafe'] : 'admin';
$createddate = date('Y-m-d H:i:s');
$lastupdatedby = isset($_SESSION['username_decafe']) ? $_SESSION['username_decafe'] : 'admin';
$lastupdateddate = date('Y-m-d H:i:s');


if (!empty($_POST['bayar_validate'])) {
    if ($kembalian < 0) {
        $message = '<script>alert("NOMINAL UANG TIDAK MENCUKUPI"); 
                    window.location="../?x=orderitem&order=' . $kode_order . '&meja=' . $meja . '&pelanggan=' . $pelanggan . '"</script>';
    } else {
        // Query insert
        $query = mysqli_query($conn, "INSERT INTO tb_bayar (id_bayar, nominal_uang, total_bayar, companycode, status, isdeleted, createdby, createddate, lastupdatedby, lastupdateddate) 
                              VALUES ('$kode_order', '$uang', '$total', '$companycode', '1', '0', '$createdby', '$createddate', '$lastupdatedby', '$lastupdateddate')");

        if ($query) {
            $message = '<script>alert("Pembayaran Berhasil \nUANG KEMBALIAN Rp. ' . $kembalian . '");
                    window.location="../?x=orderitem&order=' . $kode_order . '&meja=' . $meja . '&pelanggan=' . $pelanggan . '"</script>';
        } else {
            $message = '<script>alert("Pembayaran Gagal: ' . mysqli_error($conn) . '");
                    window.location="../?x=orderitem&order=' . $kode_order . '&meja=' . $meja . '&pelanggan=' . $pelanggan . '"</script>';
        }
    }
}
echo $message;
?>