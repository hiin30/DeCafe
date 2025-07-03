<?php
include "connect.php";
$id = (isset($_POST['id'])) ? htmlentities($_POST['id']) : "";

// Validasi input tidak boleh kosong
if (!empty($_POST['input_user_validate'])) {

    // Query update
    $query = mysqli_query($conn, "UPDATE tb_user SET password=md5('password') WHERE id='$id'");

    if ($query) { // Jika query berhasil
        $message = '<script>alert("Password berhasil di reset");
                    window.location="../user"</script>';
    } else { // Jika query gagal
        $message = '<script>alert("Password gagal di reset: ' . mysqli_error($conn) . '");</script>';
    }
    echo $message;
}
?>