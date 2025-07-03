<?php
session_start();
include "connect.php";

// Ambil data dari session dan POST
$kode_order = isset($_POST['kode_order']) ? htmlentities($_POST['kode_order']) : "";
$meja = isset($_POST['meja']) ? htmlentities($_POST['meja']) : "";
$pelanggan = isset($_POST['pelanggan']) ? htmlentities($_POST['pelanggan']) : "";
$id_user = isset($_SESSION['id_decafe']) ? $_SESSION['id_decafe'] : null;

// Validasi session ID user
if (is_null($id_user)) {
    die('<script>alert("Session ID user tidak ditemukan. Harap login ulang."); window.location="login.php";</script>');
}

if (!empty($_POST['input_order_validate'])) {
    $select = mysqli_query($conn, "SELECT * FROM tb_order WHERE id_order = '$kode_order'");
    if (mysqli_num_rows($select) > 0) {
        $message = '<script>alert("Order yang dimasukkan telah ada"); 
                    window.location="../order";</script>';
    } else {
        // Data tambahan
        $companycode = 'decafe';
        $status = 1;
        $isdeleted = 0;
        $createdby = $_SESSION['username_decafe'];
        $createddate = date('Y-m-d H:i:s');
        $lastupdateby = $_SESSION['username_decafe'];
        $lastupdateddate = date('Y-m-d H:i:s');

        // Query insert
        $query = mysqli_query($conn, "INSERT INTO tb_order 
            (id_order, meja, pelanggan, pelayan, id_user, companycode, status, isdeleted, createdby, createddate, lastupdateby, lastupdateddate) 
            VALUES 
            ('$kode_order', '$meja', '$pelanggan', '$id_user', '$id_user', 
            '$companycode', '$status', '$isdeleted', '$createdby', '$createddate', '$lastupdateby', '$lastupdateddate')");

        if ($query) {
            $message = '<script>alert("Data berhasil dimasukkan");
                      window.location="../?x=orderitem&order=' . $kode_order . '&meja=' . $meja . '&pelanggan=' . $pelanggan . '"</script>';
        } else {
            $message = '<script>alert("Data gagal dimasukkan: ' . mysqli_error($conn) . '");</script>';
        }
    }
}
echo $message;
?>
