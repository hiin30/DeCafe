<?php
include "connect.php";
$id = (isset($_POST['id'])) ? htmlentities($_POST['id']) : "";

// Validasi input tidak boleh kosong
if (!empty($_POST['hapus_kategori_validate'])) {
    $select = mysqli_query($conn, "SELECT kategori FROM tb_daftar_menu WHERE kategori = '$id'");
    if (mysqli_num_rows($select) > 0) {
        $message = '<script>alert("Kategori telah digunakan pada Daftar Menu. Kategori tidak dapat dihapus"); 
                    window.location="../katmenu";</script>';
    } else { // Query update
        $query = mysqli_query($conn, "DELETE FROM tb_kategori_menu WHERE id_kat_menu ='$id'");

        if ($query) { // Jika query berhasil
            $message = '<script>alert("Data berhasil dihapus");
                    window.location="../katmenu"</script>';
        } else { // Jika query gagal
            $message = '<script>alert("Data gagal dihapus: ' . mysqli_error($conn) . '");
                    window.location="../katmenu"</script>';
        }
    }
}
echo $message;
?>