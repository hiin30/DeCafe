<?php
include "connect.php";
$id = (isset($_POST['id'])) ? htmlentities($_POST['id']) : "";
$kode_order = (isset($_POST['kode_order'])) ? htmlentities($_POST['kode_order']) : "";
$meja = (isset($_POST['meja'])) ? htmlentities($_POST['meja']) : "";
$pelanggan = (isset($_POST['pelanggan'])) ? htmlentities($_POST['pelanggan']) : "";

// Validasi input tidak boleh kosong
if (!empty($_POST['delete_orderitem_validate'])) {

    // Query update
    $query = mysqli_query($conn, "DELETE FROM tb_list_order WHERE id_list_order ='$id'");

    if ($query) { // Jika query berhasil
        $message = '<script>alert("Data berhasil dihapus");
                    window.location="../?x=orderitem&order=' . $kode_order . '&meja=' . $meja . '&pelanggan=' . $pelanggan . '"</script>';
    } else { // Jika query gagal
        $message = '<script>alert("Data gagal dihapus: ' . mysqli_error($conn) . '");
                    window.location="../?x=orderitem&order=' . $kode_order . '&meja=' . $meja . '&pelanggan=' . $pelanggan . '"</script>';
    }
}
echo $message;
?>