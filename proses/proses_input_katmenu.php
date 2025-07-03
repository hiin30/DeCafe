<?php
include "connect.php";
session_start();

// Atur timezone ke Jakarta
date_default_timezone_set('Asia/Jakarta');

$jenismenu = (isset($_POST['jenismenu'])) ? htmlentities($_POST['jenismenu']) : "";
$katmenu = (isset($_POST['katmenu'])) ? htmlentities($_POST['katmenu']) : "";
$companycode = isset($_SESSION['companycode_decafe']) ? $_SESSION['companycode_decafe'] : 'decafe';
$status = 1;
$isdeleted = 0;
$createdby = $_SESSION['username_decafe']; // ambil dari session
$createddate = date('Y-m-d H:i:s'); // buat waktu sekarang
$lastupdatedby = $_SESSION['username_decafe'];
$lastupdateddate = date('Y-m-d H:i:s');

if (!empty($_POST['input_katmenu_validate'])) {
    $select = mysqli_query($conn, "SELECT kategori_menu FROM tb_kategori_menu WHERE kategori_menu = '$katmenu'");
    if (mysqli_num_rows($select) > 0) {
        $message = '<script>alert("Kategori yang dimasukkan telah ada"); 
                    window.location="../katmenu";</script>';
    } else { // Query insert
        $query = mysqli_query($conn, "INSERT INTO tb_kategori_menu (jenis_menu, kategori_menu, companycode, status, isdeleted, createdby, createddate, lastupdatedby, lastupdateddate)
                                  VALUES ('$jenismenu', '$katmenu', '$companycode', '$status', '$isdeleted', '$createdby', '$createddate', '$lastupdatedby', '$lastupdateddate')");
        if ($query) {
            $message = '<script>alert("Data berhasil dimasukkan");
                        window.location="../katmenu"</script>';
        } else {
            $message = '<script>alert("Data gagal dimasukkan: ' . mysqli_error($conn) . '");
                        window.location="../katmenu"</script>';
        }
    }
}
echo $message;
?>