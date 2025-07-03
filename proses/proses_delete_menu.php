<?php
include "connect.php";
$id = (isset($_POST['id'])) ? htmlentities($_POST['id']) : "";
$foto = (isset($_POST['foto'])) ? htmlentities($_POST['foto']) : "";

// Validasi input tidak boleh kosong
if (!empty($_POST['input_user_validate'])) {

    // Query update
    $query = mysqli_query($conn, "DELETE FROM tb_daftar_menu WHERE id='$id'");

    if ($query) { // Jika query berhasil
        unlink("../assets/img/$foto");
        $message = '<script>alert("Data berhasil dihapus");
                    window.location="../menu"</script>';
    } else { // Jika query gagal
        $message = '<script>alert("Data gagal dihapus: ' . mysqli_error($conn) . '");
                    window.location="../menu"</script>';
    }
    echo $message;
}
?>