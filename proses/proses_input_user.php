<?php
include "connect.php";
session_start();

// Atur timezone ke Jakarta
date_default_timezone_set('Asia/Jakarta');

$name = (isset($_POST['nama'])) ? htmlentities($_POST['nama']) : "";
$username = (isset($_POST['username'])) ? htmlentities($_POST['username']) : "";
$level = (isset($_POST['level'])) ? htmlentities($_POST['level']) : "";
$nohp = (isset($_POST['nohp'])) ? htmlentities($_POST['nohp']) : "";
$alamat = (isset($_POST['alamat'])) ? htmlentities($_POST['alamat']) : "";
$password = md5('password');
$companycode = isset($_SESSION['companycode_decafe']) ? $_SESSION['companycode_decafe'] : 'decafe';
$status = 1;
$isdeleted = 0;
$createdby = $_SESSION['username_decafe']; // ambil dari session
$createddate = date('Y-m-d H:i:s'); // buat waktu sekarang
$lastupdatedby = $_SESSION['username_decafe'];
$lastupdateddate = date('Y-m-d H:i:s');

if (!empty($_POST['input_user_validate'])) {
    $select = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$username'");
    if (mysqli_num_rows($select) > 0) {
        $message = '<script>alert("Username yang dimasukkan telah ada"); window.location="../user";</script>';
    } else {
        // Validasi data input kosong
        if ($name == "" || $username == "" || $level == "" || $nohp == "" || $alamat == "") {
            $message = '<script>alert("Semua field harus diisi");</script>';
            exit;
        }

        // Query insert
        $query = mysqli_query($conn, "INSERT INTO tb_user (nama, username, level, nohp, alamat, password, companycode, status, isdeleted, createdby, createddate, lastupdatedby, lastupdateddate) 
                                  VALUES ('$name', '$username', '$level', '$nohp', '$alamat', '$password', '$companycode', '1', '0', '$createdby', '$createddate', '$lastupdatedby', '$lastupdateddate')");
        if ($query) {
            $message = '<script>alert("Data berhasil dimasukkan");
                      window.location="../user"</script>';
        } else {
            $message = '<script>alert("Data gagal dimasukkan: ' . mysqli_error($conn) . '");</script>';
        }
    }
}
echo $message;
?>