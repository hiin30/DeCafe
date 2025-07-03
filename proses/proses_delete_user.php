<?php
include "connect.php";
$id = (isset($_POST['id'])) ? htmlentities($_POST['id']) : "";

// Validasi input tidak boleh kosong
if (!empty($_POST['input_user_validate'])) {

    // Query update
    $query = mysqli_query($conn, "DELETE FROM tb_user WHERE id='$id'");

    if ($query) { // Jika query berhasil
        $message = '<script>alert("Data berhasil dihapus");
                    window.location="../user"</script>';
    } else { // Jika query gagal
        $message = '<script>alert("Data gagal dihapus: ' . mysqli_error($conn) . '");
                    window.location="../user"</script>';
    }
    echo $message;
}
?>