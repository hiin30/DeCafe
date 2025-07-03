<?php
include "connect.php";
session_start(); // Pastikan sesi diaktifkan
date_default_timezone_set('Asia/Jakarta');

// Ambil input dari form
$id = (isset($_POST['id'])) ? htmlentities($_POST['id']) : "";
$name = (isset($_POST['nama']) && $_POST['nama'] !== "") ? htmlentities($_POST['nama']) : null;
$username = (isset($_POST['username']) && $_POST['username'] !== "") ? htmlentities($_POST['username']) : null;
$level = (isset($_POST['level']) && $_POST['level'] !== "") ? htmlentities($_POST['level']) : null;
$nohp = (isset($_POST['nohp']) && $_POST['nohp'] !== "") ? htmlentities($_POST['nohp']) : null;
$alamat = (isset($_POST['alamat']) && $_POST['alamat'] !== "") ? htmlentities($_POST['alamat']) : null;

$session_username = isset($_SESSION['username_decafe']) ? $_SESSION['username_decafe'] : null;
$lastupdatedby = $session_username ?: "Unknown User";
$last_updated_date = date('Y-m-d H:i:s'); // Format waktu sekarang

// Validasi input tidak boleh kosong
if (!empty($_POST['input_user_validate'])) {
    // Cek apakah ID atau username milik user aktif
    $current_user_check = mysqli_query($conn, "SELECT id, username FROM tb_user WHERE username='$session_username'");
    $current_user_data = mysqli_fetch_assoc($current_user_check);

    if ($current_user_data['id'] == $id || $current_user_data['username'] == $username) {
        echo '<script>alert("Anda tidak dapat mengubah data akun Anda sendiri."); window.location="../user";</script>';
        exit;
    }

    $select = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$username' AND id != '$id'");
    if ($username !== null && mysqli_num_rows($select) > 0) {
        $message = '<script>alert("Username yang dimasukkan telah ada"); window.location="../user";</script>';
    } else {
        // Buat array untuk menyimpan kolom yang akan diupdate
        $update_fields = [];
        if ($name !== null) $update_fields[] = "nama='$name'";
        if ($username !== null) $update_fields[] = "username='$username'";
        if ($level !== null) $update_fields[] = "level='$level'";
        if ($nohp !== null) $update_fields[] = "nohp='$nohp'";
        if ($alamat !== null) $update_fields[] = "alamat='$alamat'";

        // Tambahkan kolom audit (last updated by dan date)
        $update_fields[] = "lastupdatedby='$lastupdatedby'";
        $update_fields[] = "lastupdateddate='$last_updated_date'";

        // Gabungkan array menjadi string query
        $update_query = implode(", ", $update_fields);

        // Query update
        $query = mysqli_query($conn, "UPDATE tb_user SET $update_query WHERE id='$id'");

        if ($query) { // Jika query berhasil
            $message = '<script>alert("Data berhasil diupdate"); window.location="../user";</script>';
        } else { // Jika query gagal
            $message = '<script>alert("Data gagal diupdate: ' . mysqli_error($conn) . '"); window.location="../user";</script>';
        }
    }
}
echo $message;
?>
